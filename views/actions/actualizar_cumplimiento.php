<?php
// Archivo: views/actions/actualizar_cumplimiento.php
// Cambia el estado de una acción entre Cumplida (1) y Pendiente (0)

require_once __DIR__ . '/../../controllers/config/controllers_config.php';

if (isset($_GET['id']) && isset($_GET['cumplida'])) {
    $id = (int) $_GET['id'];
    $cumplida = ($_GET['cumplida'] === '1');

    $controller = obtenerRetrospectivasController();
    $controller->actualizarEstadoAccion($id, $cumplida);
}

header("Location: ../retrospectivas.php");
exit;