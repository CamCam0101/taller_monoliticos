<?php
// Archivo: views/actions/modificar_item.php
// Guarda los cambios de un ítem editado

require_once __DIR__ . '/../../controllers/config/controllers_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = obtenerRetrospectivasController();
    $controller->actualizarItem($_POST);
}

header("Location: ../retrospectivas.php");
exit;