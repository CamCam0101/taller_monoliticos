<?php
// Archivo: views/actions/agregar_item.php
// Agrega un logro, impedimento, acción o comentario a un Sprint ya creado

require_once __DIR__ . '/../../controllers/config/controllers_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = obtenerRetrospectivasController();
    $controller->agregarItemASprint($_POST);
}

header("Location: ../retrospectivas.php");
exit;