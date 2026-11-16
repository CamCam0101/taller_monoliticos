<?php
// Archivo: controllers/retrospectivas/retrospectivas_controller_interface.php
// Contrato con los casos de uso que las vistas pueden pedirle al controlador

require_once __DIR__ . '/../../models/entities/sprint.php';
require_once __DIR__ . '/../../models/entities/retro_item.php';

interface RetrospectivasControllerInterface {
    // Crea un nuevo sprint, evalúa las acciones del sprint anterior y guarda los nuevos ítems
    public function registrarRetrospectivaCompleta(array $postData): int;

    // Agrega un nuevo ítem (logro, impedimento, acción, etc.) a un sprint ya existente
    public function agregarItemASprint(array $postData): bool;

    // Trae todos los sprints junto con sus ítems agrupados para mostrar el historial
    public function obtenerHistorialCompleto(): array;

    // Trae el último sprint registrado y sus acciones para revisar cumplimiento al crear una nueva retro
    public function obtenerAccionesUltimoSprint(): array;

    // Obtiene un ítem puntual para cargarlo en el formulario de edición
    public function obtenerItemPorId(int $id): ?RetroItem;

    // Guarda los cambios de un ítem editado
    public function actualizarItem(array $postData): bool;

    // Cambia rápidamente el estado cumplida/pendiente de una acción
    public function actualizarEstadoAccion(int $itemId, bool $cumplida): bool;

    // Elimina un ítem individual
    public function eliminarItem(int $id): bool;

    // Elimina un sprint completo y sus ítems
    public function eliminarSprint(int $id): bool;
}