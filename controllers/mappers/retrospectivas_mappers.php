<?php
// Archivo: controllers/mappers/retrospectivas_mappers.php
// Convierte los datos enviados desde los formularios HTML ($_POST) en objetos Entities

require_once __DIR__ . '/../../models/entities/sprint.php';
require_once __DIR__ . '/../../models/entities/retro_item.php';

class RetrospectivasControllerMapper {

    // Convierte los datos del formulario de Sprint en un objeto Sprint
    public static function mapPostToSprint(array $postData): Sprint {
        $id = isset($postData['id']) && $postData['id'] !== '' ? (int) $postData['id'] : null;
        $nombre = trim($postData['nombre'] ?? '');
        $fechaInicio = trim($postData['fecha_inicio'] ?? '');
        $fechaFin = trim($postData['fecha_fin'] ?? '');

        return new Sprint($id, $nombre, $fechaInicio, $fechaFin);
    }

    // Convierte los datos del formulario de un ítem en un objeto RetroItem
    public static function mapPostToRetroItem(array $postData): RetroItem {
        $id = isset($postData['id']) && $postData['id'] !== '' ? (int) $postData['id'] : null;
        $sprintId = (int) ($postData['sprint_id'] ?? 0);
        $categoria = trim($postData['categoria'] ?? 'otro');
        $descripcion = trim($postData['descripcion'] ?? '');

        // Regla del SQL del profe: 'cumplida' solo aplica para 'accion'; NULL para las demás
        $cumplida = null;
        if ($categoria === 'accion') {
            if (isset($postData['cumplida'])) {
                $cumplida = ($postData['cumplida'] === '1' || $postData['cumplida'] === true);
            } else {
                // Si es una acción nueva y no viene marcada, nace como no cumplida (false)
                $cumplida = false;
            }
        }

        // Si no se envió fecha de revisión o viene vacía, la guardamos como null
        $fechaRevision = !empty($postData['fecha_revision']) ? trim($postData['fecha_revision']) : null;

        return new RetroItem(
            $id,
            $sprintId,
            $categoria,
            $descripcion,
            $cumplida,
            $fechaRevision
        );
    }
}