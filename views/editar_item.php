<?php
// Archivo: views/editar_item.php
// Permite modificar la categoría, descripción, fecha de revisión o estado de un ítem

require_once __DIR__ . '/../controllers/config/controllers_config.php';

if (!isset($_GET['id'])) {
    header("Location: retrospectivas.php");
    exit;
}

$controller = obtenerRetrospectivasController();
$item = $controller->obtenerItemPorId((int) $_GET['id']);

if ($item === null) {
    header("Location: retrospectivas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro de Retrospectiva</title>
    <link rel="stylesheet" href="../public/css/tarjetas.css">
</head>
<body>
    <div class="contenedor" style="max-width: 650px;">
        <header class="header-app">
            <div>
                <h1>Editar Registro</h1>
                <p>Actualiza la información del punto seleccionado</p>
            </div>
            <a href="retrospectivas.php" class="btn btn-secundario">Cancelar</a>
        </header>

        <form action="actions/modificar_item.php" method="POST" class="form-box">
            <input type="hidden" name="id" value="<?= $item->getId() ?>">
            <input type="hidden" name="sprint_id" value="<?= $item->getSprintId() ?>">

            <div class="form-grupo">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria" class="form-control" required>
                    <option value="logro" <?= $item->getCategoria() === 'logro' ? 'selected' : '' ?>>Logro</option>
                    <option value="impedimento" <?= $item->getCategoria() === 'impedimento' ? 'selected' : '' ?>>Impedimento</option>
                    <option value="accion" <?= $item->getCategoria() === 'accion' ? 'selected' : '' ?>>Acción</option>
                    <option value="comentario" <?= $item->getCategoria() === 'comentario' ? 'selected' : '' ?>>Comentario</option>
                    <option value="otro" <?= $item->getCategoria() === 'otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>

            <div class="form-grupo">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" class="form-control" required><?= htmlspecialchars($item->getDescripcion()) ?></textarea>
            </div>

            <div class="form-grupo">
                <label for="fecha_revision">Fecha de Revisión (opcional)</label>
                <input type="date" id="fecha_revision" name="fecha_revision" class="form-control"
                       value="<?= htmlspecialchars($item->getFechaRevision() ?? '') ?>">
            </div>

            <?php if ($item->getCategoria() === 'accion'): ?>
                <div class="form-grupo">
                    <label for="cumplida">Estado de cumplimiento (solo acciones)</label>
                    <select id="cumplida" name="cumplida" class="form-control">
                        <option value="0" <?= !$item->getCumplida() ? 'selected' : '' ?>>Pendiente / No cumplida</option>
                        <option value="1" <?= $item->getCumplida() ? 'selected' : '' ?>>Cumplida</option>
                    </select>
                </div>
            <?php endif; ?>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn btn-primario">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>