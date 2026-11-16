<?php
// Archivo: controllers/retrospectivas/retrospectivas_controller.php
// Implementa la lógica de negocio de la aplicación de Retrospectivas Scrum

require_once __DIR__ . '/retrospectivas_controller_interface.php';
require_once __DIR__ . '/../mappers/retrospectivas_mappers.php';
require_once __DIR__ . '/../../models/repositories/retrospectivas_repository_interface.php';

class RetrospectivasController implements RetrospectivasControllerInterface {
    private RetrospectivasRepositoryInterface $repository;

    // Recibimos el repositorio por inyección de dependencias (desacoplamiento POO)
    public function __construct(RetrospectivasRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    // Caso de uso principal: crear nueva retrospectiva + revisar acciones anteriores
    public function registrarRetrospectivaCompleta(array $postData): int {
        // 1. Si en el formulario se evaluaron acciones del sprint anterior, actualizamos su estado
        if (!empty($postData['acciones_previas']) && is_array($postData['acciones_previas'])) {
            foreach ($postData['acciones_previas'] as $accionId => $estado) {
                $cumplida = ($estado === '1');
                $this->repository->actualizarCumplimiento((int) $accionId, $cumplida);
            }
        }

        // 2. Mapeamos y guardamos el nuevo Sprint en la base de datos
        $nuevoSprint = RetrospectivasControllerMapper::mapPostToSprint($postData);
        $nuevoSprintId = $this->repository->crearSprint($nuevoSprint);

        // 3. Guardamos todos los ítems registrados para este nuevo Sprint (logros, impedimentos, acciones...)
        if (!empty($postData['descripciones']) && is_array($postData['descripciones'])) {
            $categorias = $postData['categorias'] ?? [];
            $descripciones = $postData['descripciones'];
            $fechasRevision = $postData['fechas_revision'] ?? [];

            foreach ($descripciones as $index => $texto) {
                $textoLimpio = trim($texto);
                // Ignoramos filas que el usuario haya dejado en blanco
                if ($textoLimpio === '') {
                    continue;
                }

                $itemData = [
                    'sprint_id'      => $nuevoSprintId,
                    'categoria'      => $categorias[$index] ?? 'otro',
                    'descripcion'    => $textoLimpio,
                    'fecha_revision' => $fechasRevision[$index] ?? null
                ];

                $item = RetrospectivasControllerMapper::mapPostToRetroItem($itemData);
                $this->repository->crearItem($item);
            }
        }

        return $nuevoSprintId;
    }

    // Añade un ítem puntual dentro de una retrospectiva que ya existe
    public function agregarItemASprint(array $postData): bool {
        if (empty(trim($postData['descripcion'] ?? ''))) {
            return false;
        }
        $item = RetrospectivasControllerMapper::mapPostToRetroItem($postData);
        return $this->repository->crearItem($item);
    }

    // Devuelve un arreglo donde cada elemento tiene el objeto Sprint y su lista de RetroItems
    public function obtenerHistorialCompleto(): array {
        $sprints = $this->repository->obtenerTodosLosSprints();
        $historial = [];

        foreach ($sprints as $sprint) {
            $items = $this->repository->obtenerItemsPorSprint($sprint->getId());
            $historial[] = [
                'sprint' => $sprint,
                'items'  => $items
            ];
        }

        return $historial;
    }

    // Busca el sprint más reciente y extrae sus 'acciones' para evaluarlas en la nueva retro
    public function obtenerAccionesUltimoSprint(): array {
        $ultimoSprint = $this->repository->obtenerUltimoSprint();

        if ($ultimoSprint === null) {
            return [
                'sprint'   => null,
                'acciones' => []
            ];
        }

        $acciones = $this->repository->obtenerAccionesPorSprint($ultimoSprint->getId());

        return [
            'sprint'   => $ultimoSprint,
            'acciones' => $acciones
        ];
    }

    public function obtenerItemPorId(int $id): ?RetroItem {
        return $this->repository->obtenerItemPorId($id);
    }

    public function actualizarItem(array $postData): bool {
        $item = RetrospectivasControllerMapper::mapPostToRetroItem($postData);
        return $this->repository->actualizarItem($item);
    }

    public function actualizarEstadoAccion(int $itemId, bool $cumplida): bool {
        return $this->repository->actualizarCumplimiento($itemId, $cumplida);
    }

    public function eliminarItem(int $id): bool {
        return $this->repository->eliminarItem($id);
    }

    public function eliminarSprint(int $id): bool {
        return $this->repository->eliminarSprint($id);
    }
}