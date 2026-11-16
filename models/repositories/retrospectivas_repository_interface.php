<?php
// Archivo: models/repositories/retrospectivas_repository_interface.php
// Contrato con todas las operaciones de datos que necesita nuestra aplicación

require_once __DIR__ . '/../entities/sprint.php';
require_once __DIR__ . '/../entities/retro_item.php';

interface RetrospectivasRepositoryInterface {
    // --- Operaciones sobre Sprints ---
    public function crearSprint(Sprint $sprint): int;
    public function obtenerTodosLosSprints(): array;
    public function obtenerSprintPorId(int $id): ?Sprint;
    public function obtenerUltimoSprint(): ?Sprint;
    public function eliminarSprint(int $id): bool;

    // --- Operaciones sobre Ítems (logros, impedimentos, acciones, etc.) ---
    public function crearItem(RetroItem $item): bool;
    public function obtenerItemsPorSprint(int $sprintId): array;
    public function obtenerAccionesPorSprint(int $sprintId): array;
    public function obtenerItemPorId(int $id): ?RetroItem;
    public function actualizarItem(RetroItem $item): bool;
    public function actualizarCumplimiento(int $id, bool $cumplida): bool;
    public function eliminarItem(int $id): bool;
}