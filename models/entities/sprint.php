<?php
// Archivo: models/entities/sprint.php
// Esta clase representa un registro de la tabla 'sprints' de la base de datos

class Sprint {
    // 1. ATRIBUTOS (Propiedades privadas = Encapsulamiento en POO)
    // El signo '?' significa que el dato puede ser null (por ejemplo, el id antes de guardarse en MySQL)
    private ?int $id;
    private string $nombre;
    private string $fechaInicio;
    private string $fechaFin;
    private ?string $createdAt;
    private ?string $updatedAt;

    // 2. CONSTRUCTOR
    // Se ejecuta automáticamente cuando hacemos "new Sprint(...)"
    public function __construct(
        ?int $id,
        string $nombre,
        string $fechaInicio,
        string $fechaFin,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // 3. GETTERS (Métodos públicos para leer los atributos privados desde otras capas)
    public function getId(): ?int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getFechaInicio(): string {
        return $this->fechaInicio;
    }

    public function getFechaFin(): string {
        return $this->fechaFin;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }

    // 4. SETTERS (Métodos públicos para modificar atributos si hace falta)
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setFechaInicio(string $fechaInicio): void {
        $this->fechaInicio = $fechaInicio;
    }

    public function setFechaFin(string $fechaFin): void {
        $this->fechaFin = $fechaFin;
    }
}