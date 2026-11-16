<?php
// Archivo: models/entities/retro_item.php
// Esta clase representa cada tarjeta o punto discutido en la tabla 'retro_items'

class RetroItem {
    // 1. ATRIBUTOS PRIVADOS (Mapean las columnas de la tabla retro_items)
    private ?int $id;
    private int $sprintId;
    private string $categoria;       // 'accion', 'logro', 'impedimento', 'comentario', 'otro'
    private string $descripcion;
    private ?bool $cumplida;         // Puede ser true, false o null (null cuando no es una acción)
    private ?string $fechaRevision;  // Fecha opcional para revisar cumplimiento o seguimiento
    private ?string $createdAt;
    private ?string $updatedAt;

    // 2. CONSTRUCTOR
    public function __construct(
        ?int $id,
        int $sprintId,
        string $categoria,
        string $descripcion,
        ?bool $cumplida = null,
        ?string $fechaRevision = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->sprintId = $sprintId;
        $this->categoria = $categoria;
        $this->descripcion = $descripcion;
        $this->cumplida = $cumplida;
        $this->fechaRevision = $fechaRevision;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // 3. GETTERS
    public function getId(): ?int {
        return $this->id;
    }

    public function getSprintId(): int {
        return $this->sprintId;
    }

    public function getCategoria(): string {
        return $this->categoria;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    // En booleanos que pueden ser null se suele nombrar getCumplida()
    public function getCumplida(): ?bool {
        return $this->cumplida;
    }

    public function getFechaRevision(): ?string {
        return $this->fechaRevision;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }

    // 4. SETTERS
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setCategoria(string $categoria): void {
        $this->categoria = $categoria;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setCumplida(?bool $cumplida): void {
        $this->cumplida = $cumplida;
    }

    public function setFechaRevision(?string $fechaRevision): void {
        $this->fechaRevision = $fechaRevision;
    }
}