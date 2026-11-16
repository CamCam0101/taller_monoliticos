<?php
// Archivo: views/retrospectivas.php
// Pantalla principal: muestra el historial de Sprints y sus tarjetas de retrospectiva

require_once __DIR__ . '/../controllers/config/controllers_config.php';

$controller = obtenerRetrospectivasController();
$historial = $controller->obtenerHistorialCompleto();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Retrospectivas Ágiles</title>
    <link rel="stylesheet" href="../public/css/tarjetas.css">
</head>

<body>
    <div class="contenedor">

        <div class="hud-bar">
            <span>♥ ♥ ♥ SCRUM SYSTEM</span>
            <span>[■■■] ONLINE</span>
        </div>

        <header class="header-app">
            <p class="subtitulo-pixel">❖ GESTIÓN ÁGIL Y MEJORA CONTINUA ❖</p>
            <h1>Registro de Retrospectivas
                <br> Scrum</h1>
            <p class="desc-header">Aquí controlaremos el historial de sprints, logros, impedimentos y seguimiento de compromisos</p>
            <div class="estrellas-deco">✦ &nbsp; ✧ &nbsp; ✦</div>

            <?php if (!empty($historial)): ?>
                <div class="grupo-botones-inicio">
                    <a href="formulario_retrospectiva.php" class="btn btn-primario">+ Nueva Retrospectiva</a>
                </div>
            <?php endif; ?>
        </header>

        <?php if (empty($historial)): ?>
            <div class="form-box estado-vacio">
                <h3>Aún no hay retrospectivas registradas</h3>
                <p>Crea la primera retrospectiva de tu equipo Scrum para empezar a documentar mejoras.</p>
                <a href="formulario_retrospectiva.php" class="btn btn-primario">+ Crear retrospectiva</a>
            </div>
        <?php else: ?>
            <?php foreach ($historial as $registro): ?>
                <?php
                /** @var Sprint $sprint */
                $sprint = $registro['sprint'];
                /** @var RetroItem[] $items */
                $items = $registro['items'];
                ?>
                <section class="sprint-card">
                    <div class="sprint-header">
                        <div>
                            <h2><?= htmlspecialchars($sprint->getNombre()) ?></h2>
                            <div class="sprint-fechas">
                                Del <strong><?= htmlspecialchars($sprint->getFechaInicio()) ?></strong>
                                al <strong><?= htmlspecialchars($sprint->getFechaFin()) ?></strong>
                            </div>
                        </div>
                        <a href="actions/eliminar_sprint.php?id=<?= $sprint->getId() ?>"
                            class="btn btn-peligro btn-sm"
                            onclick="return confirm('¿Seguro que deseas eliminar todo este Sprint y sus registros?');">
                            Eliminar Sprint
                        </a>
                    </div>

                    <!-- Tarjetas de ítems del Sprint -->
                    <?php if (empty($items)): ?>
                        <p style="color: #718096; margin-bottom: 15px;">Este sprint aún no tiene ítems registrados.</p>
                    <?php else: ?>
                        <div class="grid-items">
                            <?php foreach ($items as $item): ?>
                                <?php $cat = $item->getCategoria(); ?>
                                <article class="item-card cat-<?= htmlspecialchars($cat) ?>">
                                    <div>
                                        <span class="badge badge-<?= htmlspecialchars($cat) ?>">
                                            <?= htmlspecialchars($cat) ?>
                                        </span>
                                        <p class="item-desc"><?= nl2br(htmlspecialchars($item->getDescripcion())) ?></p>
                                    </div>

                                    <div>
                                        <?php if ($item->getFechaRevision()): ?>
                                            <div class="item-meta">
                                                Revisión: <strong><?= htmlspecialchars($item->getFechaRevision()) ?></strong>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Estado de cumplimiento: solo aplica para 'accion' -->
                                        <?php if ($cat === 'accion'): ?>
                                            <div class="item-meta">
                                                Estado:
                                                <?php if ($item->getCumplida()): ?>
                                                    <span class="estado-cumplida">Cumplida</span>
                                                <?php else: ?>
                                                    <span class="estado-pendiente">Pendiente / No cumplida</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="item-acciones">
                                            <?php if ($cat === 'accion'): ?>
                                                <?php if ($item->getCumplida()): ?>
                                                    <a href="actions/actualizar_cumplimiento.php?id=<?= $item->getId() ?>&cumplida=0"
                                                        class="btn btn-secundario btn-sm">Marcar Pendiente</a>
                                                <?php else: ?>
                                                    <a href="actions/actualizar_cumplimiento.php?id=<?= $item->getId() ?>&cumplida=1"
                                                        class="btn btn-exito btn-sm">Marcar Cumplida</a>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <a href="editar_item.php?id=<?= $item->getId() ?>" class="btn btn-primario btn-sm">Editar</a>
                                            <a href="actions/eliminar_item.php?id=<?= $item->getId() ?>"
                                                class="btn btn-peligro btn-sm"
                                                onclick="return confirm('¿Eliminar este registro?');">Borrar</a>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario rápido para añadir un punto adicional al Sprint -->
                    <form action="actions/agregar_item.php" method="POST" class="form-rapido">
                        <input type="hidden" name="sprint_id" value="<?= $sprint->getId() ?>">
                        <select name="categoria" class="form-control" style="max-width: 170px;" required>
                            <option value="logro">Logro</option>
                            <option value="impedimento">Impedimento</option>
                            <option value="accion">Acción</option>
                            <option value="comentario">Comentario</option>
                            <option value="otro">Otro</option>
                        </select>
                        <input type="text" name="descripcion" class="form-control" style="flex: 1; min-width: 220px;"
                            placeholder="Agregar otro logro, impedimento o acción a este sprint..." required>
                        <input type="date" name="fecha_revision" class="form-control" style="max-width: 160px;"
                            title="Fecha opcional de revisión">
                        <button type="submit" class="btn btn-primario btn-sm">+ Agregar</button>
                    </form>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>