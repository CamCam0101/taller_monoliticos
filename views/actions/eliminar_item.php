<?php
// Archivo: views/actions/eliminar_item.php
// Elimina una tarjeta individual de una retrospectiva

require_once __DIR__ . '/../../controllers/config/controllers_config.php';

if (isset($_GET['id'])) {
    $controller = obtenerRetrospectivasController();
    $controller->eliminarItem((int) $_GET['id']);
}

header("Location: ../retrospectivas.php");
exit;