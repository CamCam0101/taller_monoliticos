<?php
// Archivo: views/actions/eliminar_sprint.php
// Elimina un sprint completo (y por ON DELETE CASCADE en MySQL, todos sus ítems)

require_once __DIR__ . '/../../controllers/config/controllers_config.php';

if (isset($_GET['id'])) {
    $controller = obtenerRetrospectivasController();
    $controller->eliminarSprint((int) $_GET['id']);
}

header("Location: ../retrospectivas.php");
exit;