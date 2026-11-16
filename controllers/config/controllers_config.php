<?php
// Archivo: controllers/config/controllers_config.php
// Carga centralizada de los controladores y ensamblaje de dependencias (BD -> Repo -> Controller)

require_once __DIR__ . '/../../models/config/models_config.php';
require_once __DIR__ . '/../mappers/retrospectivas_mappers.php';
require_once __DIR__ . '/../retrospectivas/retrospectivas_controller_interface.php';
require_once __DIR__ . '/../retrospectivas/retrospectivas_controller.php';

// Función auxiliar para instanciar el controlador listo para usar desde cualquier vista o acción
function obtenerRetrospectivasController(): RetrospectivasController {
    $db = new RetrospectivasDb();
    $repository = new RetrospectivasRepository($db);
    return new RetrospectivasController($repository);
}