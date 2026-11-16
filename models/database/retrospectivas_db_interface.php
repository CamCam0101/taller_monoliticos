<?php
// Archivo: models/database/retrospectivas_db_interface.php
// Esta interfaz define el "contrato" que debe cumplir cualquier clase que se conecte a la BD

interface RetrospectivasDbInterface {
    // Obliga a que la clase tenga un método público que devuelva la conexión activa de mysqli
    public function getConnection(): mysqli;
}