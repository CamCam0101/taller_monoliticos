<?php
// Archivo: views/formulario_retrospectiva.php
// Formulario para registrar un nuevo Sprint y evaluar las acciones del Sprint anterior

require_once __DIR__ . '/../controllers/config/controllers_config.php';

$controller = obtenerRetrospectivasController();
// Consultamos si existe un sprint previo y qué acciones dejó pendientes/acordadas
$datosPrevios = $controller->obtenerAccionesUltimoSprint();
$sprintAnterior = $datosPrevios['sprint'];
$accionesAnteriores = $datosPrevios['acciones'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Retrospectiva Scrum</title>
    <link rel="stylesheet" href="../public/css/tarjetas.css">
</head>
<body>
    <div class="contenedor">
        <header class="header-app">
            <div>
                <h1>Nueva Reunión de Retrospectiva</h1>
                <p>Revisa compromisos anteriores y documenta los puntos del nuevo sprint</p>
            </div>
            <a href="retrospectivas.php" class="btn btn-secundario">Volver al historial</a>
        </header>

        <form action="actions/registrar_retrospectiva.php" method="POST">
            <!-- SECCIÓN 1: Revisión de acciones del sprint anterior (si existen) -->
            <?php if ($sprintAnterior !== null && !empty($accionesAnteriores)): ?>
                <div class="caja-revision">
                    <h3>Seguimiento: Acciones acordadas en <?= htmlspecialchars($sprintAnterior->getNombre()) ?></h3>
                    <p style="font-size: 0.88rem; margin-bottom: 12px; color: #744210;">
                        Antes de iniciar la nueva retrospectiva, evalúa si el equipo cumplió los compromisos anteriores:
                    </p>

                    <?php foreach ($accionesAnteriores as $accion): ?>
                        <div class="accion-previa-item">
                            <div>
                                <strong><?= htmlspecialchars($accion->getDescripcion()) ?></strong>
                                <?php if ($accion->getFechaRevision()): ?>
                                    <span style="font-size: 0.8rem; color: #718096;">
                                        (Revisión: <?= htmlspecialchars($accion->getFechaRevision()) ?>)
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label style="margin-right: 12px; cursor: pointer;">
                                    <input type="radio"
                                           name="acciones_previas[<?= $accion->getId() ?>]"
                                           value="1"
                                           <?= $accion->getCumplida() ? 'checked' : '' ?>>
                                    Cumplida
                                </label>
                                <label style="cursor: pointer;">
                                    <input type="radio"
                                           name="acciones_previas[<?= $accion->getId() ?>]"
                                           value="0"
                                           <?= !$accion->getCumplida() ? 'checked' : '' ?>>
                                    No cumplida
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- SECCIÓN 2: Datos del nuevo Sprint -->
            <div class="form-box">
                <h3 style="margin-bottom: 15px;">1. Información del Sprint</h3>
                <div class="fila-grid">
                    <div class="form-grupo">
                        <label for="nombre">Nombre del Sprint *</label>
                        <input type="text" id="nombre" name="nombre" class="form-control"
                               placeholder="Ej: Sprint 1 - Módulo de Usuarios" required>
                    </div>
                    <div class="form-grupo">
                        <label for="fecha_inicio">Fecha de Inicio *</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="form-grupo">
                        <label for="fecha_fin">Fecha de Fin *</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: Puntos discutidos en la retrospectiva -->
            <div class="form-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3>2. Puntos de la Retrospectiva (Logros, Impedimentos, Acciones)</h3>
                    <button type="button" class="btn btn-exito btn-sm" onclick="agregarFilaItem()">+ Agregar otro punto</button>
                </div>

                <div id="contenedor-items">
                    <!-- Fila inicial por defecto: Logro -->
                    <div class="fila-grid item-row" style="margin-bottom: 12px; align-items: end;">
                        <div class="form-grupo" style="margin-bottom: 0;">
                            <label>Categoría</label>
                            <select name="categorias[]" class="form-control">
                                <option value="logro" selected>Logro (Lo que funcionó bien)</option>
                                <option value="impedimento">Impedimento (A mejorar)</option>
                                <option value="accion">Acción (Compromiso próximo sprint)</option>
                                <option value="comentario">Comentario</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-grupo" style="margin-bottom: 0; grid-column: span 2;">
                            <label>Descripción</label>
                            <input type="text" name="descripciones[]" class="form-control"
                                   placeholder="Describe el punto discutido..." required>
                        </div>
                        <div class="form-grupo" style="margin-bottom: 0;">
                            <label>Fecha revisión (opcional)</label>
                            <input type="date" name="fechas_revision[]" class="form-control">
                        </div>
                    </div>

                    <!-- Segunda fila por defecto: Impedimento -->
                    <div class="fila-grid item-row" style="margin-bottom: 12px; align-items: end;">
                        <div class="form-grupo" style="margin-bottom: 0;">
                            <select name="categorias[]" class="form-control">
                                <option value="logro">Logro (Lo que funcionó bien)</option>
                                <option value="impedimento" selected>Impedimento (A mejorar)</option>
                                <option value="accion">Acción (Compromiso próximo sprint)</option>
                                <option value="comentario">Comentario</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-grupo" style="margin-bottom: 0; grid-column: span 2;">
                            <input type="text" name="descripciones[]" class="form-control"
                                   placeholder="Describe el impedimento encontrado...">
                        </div>
                        <div class="form-grupo" style="margin-bottom: 0;">
                            <input type="date" name="fechas_revision[]" class="form-control">
                        </div>
                    </div>

                    <!-- Tercera fila por defecto: Acción -->
                    <div class="fila-grid item-row" style="margin-bottom: 12px; align-items: end;">
                        <div class="form-grupo" style="margin-bottom: 0;">
                            <select name="categorias[]" class="form-control">
                                <option value="logro">Logro (Lo que funcionó bien)</option>
                                <option value="impedimento">Impedimento (A mejorar)</option>
                                <option value="accion" selected>Acción (Compromiso próximo sprint)</option>
                                <option value="comentario">Comentario</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-grupo" style="margin-bottom: 0; grid-column: span 2;">
                            <input type="text" name="descripciones[]" class="form-control"
                                   placeholder="Describe la acción o acuerdo asumido...">
                        </div>
                        <div class="form-grupo" style="margin-bottom: 0;">
                            <input type="date" name="fechas_revision[]" class="form-control">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primario">Guardar Retrospectiva</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Script sencillo para añadir dinámicamente más filas de logros, impedimentos o acciones
        function agregarFilaItem() {
            const contenedor = document.getElementById('contenedor-items');
            const fila = document.createElement('div');
            fila.className = 'fila-grid item-row';
            fila.style.marginBottom = '12px';
            fila.style.alignItems = 'end';

            fila.innerHTML = `
                <div class="form-grupo" style="margin-bottom: 0;">
                    <select name="categorias[]" class="form-control">
                        <option value="logro">Logro (Lo que funcionó bien)</option>
                        <option value="impedimento">Impedimento (A mejorar)</option>
                        <option value="accion">Acción (Compromiso próximo sprint)</option>
                        <option value="comentario">Comentario</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-grupo" style="margin-bottom: 0; grid-column: span 2;">
                    <input type="text" name="descripciones[]" class="form-control" placeholder="Escribe otro punto...">
                </div>
                <div class="form-grupo" style="margin-bottom: 0;">
                    <input type="date" name="fechas_revision[]" class="form-control">
                </div>
            `;
            contenedor.appendChild(fila);
        }
    </script>
</body>
</html>