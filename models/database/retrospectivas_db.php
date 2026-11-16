<?php
// Archivo: models/database/retrospectivas_db.php
// Esta clase implementa la interfaz y se encarga de abrir la conexión con MySQL en XAMPP

// Importamos la interfaz para poder implementarla
require_once __DIR__ . '/retrospectivas_db_interface.php';

class RetrospectivasDb implements RetrospectivasDbInterface {
    // Credenciales por defecto de XAMPP en local
    private string $host = 'localhost';
    private string $user = 'root';
    private string $password = ''; // En XAMPP la contraseña de root viene vacía por defecto
    private string $database = 'registro_retro_db'; // Nombre exacto de la BD del profe
    
    // Aquí guardaremos el objeto de conexión de mysqli
    private ?mysqli $connection = null;

    // El constructor se ejecuta apenas hacemos "new RetrospectivasDb()"
    public function __construct() {
        // Instanciamos la conexión nativa de PHP hacia MySQL
        $this->connection = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->database
        );

        // Si MySQL está apagado en XAMPP o el nombre de la BD está mal, detenemos todo y avisamos
        if ($this->connection->connect_error) {
            die("Error de conexión a MySQL: " . $this->connection->connect_error);
        }

        // Configuramos utf8mb4 para que las tildes y las 'ñ' se guarden y lean bien
        $this->connection->set_charset("utf8mb4");
    }

    // Método exigido por la interfaz para entregarle la conexión a los Repositorios
    public function getConnection(): mysqli {
        return $this->connection;
    }
}