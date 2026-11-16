<?php
// Archivo: models/mappers/retrospectivas_mappers.php
// Convierte filas crudas de la base de datos (arrays) en objetos POO (Entities)

require_once __DIR__ . '/../entities/sprint.php';
require_once __DIR__ . '/../entities/retro_item.php';

class RetrospectivasModelMapper {

    // Transforma una fila de la tabla 'sprints' en un objeto Sprint
    public static function mapRowToSprint(array $row): Sprint {
        return new Sprint(
            (int) $row['id'],
            $row['nombre'],
            $row['fecha_inicio'],
            $row['fecha_fin'],
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }

    // Transforma una fila de la tabla 'retro_items' en un objeto RetroItem
    public static function mapRowToRetroItem(array $row): RetroItem {
        // En MySQL, BOOLEAN se guarda como 1, 0 o NULL. Aquí lo pasamos a bool|null de PHP
        $cumplida = null;
        if ($row['cumplida'] !== null) {
            $cumplida = ((int) $row['cumplida']) === 1;
        }

        return new RetroItem(
            (int) $row['id'],
            (int) $row['sprint_id'],
            $row['categoria'],
            $row['descripcion'],
            $cumplida,
            $row['fecha_revision'] ?? null,
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }
}