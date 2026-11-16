<?php
// Archivo: views/actions/registrar_retrospectiva.php
// Procesa el formulario de creación de una nueva retrospectiva de Sprint

require_once __DIR__ . '/../../controllers/config/controllers_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = obtenerRetrospectivasController();
    $controller->registrarRetrospectivaCompleta($_POST);
}

header("Location: ../retrospectivas.php");
exit;