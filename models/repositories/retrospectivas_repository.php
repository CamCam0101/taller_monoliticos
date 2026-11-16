<?php
// Archivo: models/repositories/retrospectivas_repository.php
// Implementa las consultas SQL hacia MySQL usando la conexión de RetrospectivasDb

require_once __DIR__ . '/retrospectivas_repository_interface.php';
require_once __DIR__ . '/../database/retrospectivas_db_interface.php';
require_once __DIR__ . '/../mappers/retrospectivas_mappers.php';

class RetrospectivasRepository implements RetrospectivasRepositoryInterface {
    private mysqli $conn;

    // Inyección de dependencias: recibimos la interfaz de BD en el constructor
    public function __construct(RetrospectivasDbInterface $db) {
        $this->conn = $db->getConnection();
    }

    // Guarda un nuevo sprint y devuelve el ID autogenerado por MySQL
    public function crearSprint(Sprint $sprint): int {
        $sql = "INSERT INTO sprints (nombre, fecha_inicio, fecha_fin) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $nombre = $sprint->getNombre();
        $fechaInicio = $sprint->getFechaInicio();
        $fechaFin = $sprint->getFechaFin();

        // "sss" indica que enviamos 3 parámetros de tipo string
        $stmt->bind_param("sss", $nombre, $fechaInicio, $fechaFin);
        $stmt->execute();

        $nuevoId = $stmt->insert_id;
        $stmt->close();

        return $nuevoId;
    }

    // Trae todos los sprints ordenados del más reciente al más antiguo
    public function obtenerTodosLosSprints(): array {
        $sql = "SELECT * FROM sprints ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $sprints = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sprints[] = RetrospectivasModelMapper::mapRowToSprint($row);
            }
        }
        return $sprints;
    }

    // Busca un sprint específico por su ID
    public function obtenerSprintPorId(int $id): ?Sprint {
        $sql = "SELECT * FROM sprints WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? RetrospectivasModelMapper::mapRowToSprint($row) : null;
    }

    // Obtiene el último sprint registrado (clave para cargar las acciones de la retro anterior)
    public function obtenerUltimoSprint(): ?Sprint {
        $sql = "SELECT * FROM sprints ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            return RetrospectivasModelMapper::mapRowToSprint($row);
        }
        return null;
    }

    // Elimina un sprint (por ON DELETE CASCADE en el SQL, también borra sus ítems)
    public function eliminarSprint(int $id): bool {
        $sql = "DELETE FROM sprints WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // Inserta un logro, impedimento, acción, comentario u otro dentro de un sprint
    public function crearItem(RetroItem $item): bool {
        $sql = "INSERT INTO retro_items (sprint_id, categoria, descripcion, cumplida, fecha_revision) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $sprintId = $item->getSprintId();
        $categoria = $item->getCategoria();
        $descripcion = $item->getDescripcion();
        // Convertimos bool|null a int|null para guardarlo en MySQL
        $cumplida = $item->getCumplida() === null ? null : ($item->getCumplida() ? 1 : 0);
        $fechaRevision = $item->getFechaRevision();

        // "issis" = int, string, string, int(o null), string(o null)
        $stmt->bind_param("issis", $sprintId, $categoria, $descripcion, $cumplida, $fechaRevision);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // Trae todos los ítems asociados a un sprint
    public function obtenerItemsPorSprint(int $sprintId): array {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = ? ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();

        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = RetrospectivasModelMapper::mapRowToRetroItem($row);
        }
        $stmt->close();

        return $items;
    }

    // Trae ÚNICAMENTE los ítems de categoría 'accion' de un sprint (para revisar compromisos)
    public function obtenerAccionesPorSprint(int $sprintId): array {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = ? AND categoria = 'accion' ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sprintId);
        $stmt->execute();

        $result = $stmt->get_result();
        $acciones = [];
        while ($row = $result->fetch_assoc()) {
            $acciones[] = RetrospectivasModelMapper::mapRowToRetroItem($row);
        }
        $stmt->close();

        return $acciones;
    }

    // Busca un ítem puntual por ID (útil cuando vamos a editarlo)
    public function obtenerItemPorId(int $id): ?RetroItem {
        $sql = "SELECT * FROM retro_items WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? RetrospectivasModelMapper::mapRowToRetroItem($row) : null;
    }

    // Edita la categoría, descripción, estado de cumplimiento o fecha de revisión de un ítem
    public function actualizarItem(RetroItem $item): bool {
        $sql = "UPDATE retro_items 
                SET categoria = ?, descripcion = ?, cumplida = ?, fecha_revision = ? 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        $categoria = $item->getCategoria();
        $descripcion = $item->getDescripcion();
        $cumplida = $item->getCumplida() === null ? null : ($item->getCumplida() ? 1 : 0);
        $fechaRevision = $item->getFechaRevision();
        $id = $item->getId();

        $stmt->bind_param("ssisi", $categoria, $descripcion, $cumplida, $fechaRevision, $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // Marca rápidamente si una acción del sprint anterior se cumplió (true) o no (false)
    public function actualizarCumplimiento(int $id, bool $cumplida): bool {
        $sql = "UPDATE retro_items SET cumplida = ? WHERE id = ? AND categoria = 'accion'";
        $stmt = $this->conn->prepare($sql);

        $valor = $cumplida ? 1 : 0;
        $stmt->bind_param("ii", $valor, $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // Borra un ítem individual
    public function eliminarItem(int $id): bool {
        $sql = "DELETE FROM retro_items WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}