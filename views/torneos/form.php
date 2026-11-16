<?php
$torneo = $torneo ?? [];
$errores = $errores ?? [];
$stats = $stats ?? ['total' => 0, 'con_resultado' => 0];
$isEdit = !empty($torneo['id_torneo']);
$titulo = $isEdit ? 'Editar' : 'Nuevo';
?>

<?= renderErroresGenerales($errores) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-trophy"></i> <?= $titulo ?> Torneo</h2>
    <a href="?c=torneos&a=index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="?c=torneos&a=guardar" id="torneoForm" data-validate novalidate>
            <input type="hidden" name="id" value="<?= old('id', $torneo['id_torneo'] ?? '') ?>">
            
            <!-- SECCIÓN 1: INFORMACIÓN DEL TORNEO -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Torneo</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Nombre del torneo -->
                        <div class="col-md-12">
                            <label for="nombre_torneo" class="form-label required">Nombre del Torneo</label>
                            <input type="text" name="nombre_torneo" id="nombre_torneo" class="<?= fieldClass('nombre_torneo', $errores) ?>" required 
                                   minlength="3" maxlength="100"
                                   pattern="[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,&amp;\(\)]+"
                                   value="<?= old('nombre_torneo', $torneo['nombre_torneo'] ?? '') ?>"
                                   placeholder="Ej: Campeonato Nacional de Boxeo 2025"
                                   data-msg-required="El nombre del torneo es obligatorio"
                                   data-msg-minlength="Mínimo 3 caracteres"
                                   data-msg-maxlength="Máximo 100 caracteres"
                                   data-msg-pattern="Solo letras, números, espacios y símbolos básicos"
                                   data-msg-valid="Nombre válido"
                                   data-hint="Entre 3 y 100 caracteres. Debe ser único.">
                            <div class="invalid-feedback d-block"><?= errorMsg('nombre_torneo', $errores) ?></div>
                        </div>
                        
                        <!-- Fecha de Inicio -->
                        <div class="col-md-6">
                            <label for="fechaInicio" class="form-label required">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fechaInicio" class="<?= fieldClass('fecha_inicio', $errores) ?>" required 
                                   value="<?= old('fecha_inicio', $torneo['fecha_inicio'] ?? date('Y-m-d')) ?>"
                                   data-msg-required="La fecha de inicio es obligatoria"
                                   data-msg-valid="Fecha válida"
                                   data-hint="Fecha en que comienza el torneo">
                            <div class="invalid-feedback d-block"><?= errorMsg('fecha_inicio', $errores) ?></div>
                        </div>
                        
                        <!-- Fecha de Fin -->
                        <div class="col-md-6">
                            <label for="fechaFin" class="form-label">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" id="fechaFin" class="<?= fieldClass('fecha_fin', $errores) ?>" 
                                   value="<?= old('fecha_fin', $torneo['fecha_fin'] ?? '') ?>"
                                   data-msg-valid="Fecha válida"
                                   data-hint="Opcional. Debe ser igual o posterior a la fecha de inicio">
                            <div class="invalid-feedback d-block"><?= errorMsg('fecha_fin', $errores) ?></div>
                        </div>
                        
                        <!-- Lugar -->
                        <div class="col-md-12">
                            <label for="lugar" class="form-label">Lugar / Ubicación</label>
                            <input type="text" name="lugar" id="lugar" class="<?= fieldClass('lugar', $errores) ?>" 
                                   maxlength="255"
                                   pattern="[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,#°ºª]+"
                                   value="<?= old('lugar', $torneo['lugar'] ?? '') ?>"
                                   placeholder="Ej: Gimnasio Olímpico, Caracas"
                                   data-msg-pattern="Solo letras, números y símbolos básicos"
                                   data-msg-valid="Lugar válido"
                                   data-hint="Dirección o lugar donde se realizará el torneo">
                            <div class="invalid-feedback d-block"><?= errorMsg('lugar', $errores) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN 2: INFO DE COMBATES (SOLO EDICIÓN) -->
            <?php if ($isEdit): ?>
            <div class="card mb-4 bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Combates del Torneo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <i class="bi bi-fire" style="font-size: 2rem; color: #c8102e;"></i>
                                <h3 class="mb-0"><?= (int)$stats['total'] ?></h3>
                                <small class="text-muted">Combates registrados</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <i class="bi bi-trophy" style="font-size: 2rem; color: #ffc107;"></i>
                                <h3 class="mb-0"><?= (int)$stats['con_resultado'] ?></h3>
                                <small class="text-muted">Combates con resultado</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="?c=torneos&a=llaves&id=<?= $torneo['id_torneo'] ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-diagram-3"></i> Ver Llaves del Torneo
                        </a>
                        <a href="?c=combates&a=form&id_torneo=<?= $torneo['id_torneo'] ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-lg"></i> Agregar Combate
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- BOTONES -->
            <div class="d-flex gap-2 justify-content-end">
                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</button>
                <a href="?c=torneos&a=index" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary" disabled>
                    <i class="bi bi-save"></i> Guardar Torneo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================================
// VALIDACIÓN CRUZADA: fecha_fin >= fecha_inicio
// Se usa data-min dinámico para que validaciones.js lo detecte
// ============================================================
(function() {
    var fechaInicio = document.getElementById('fechaInicio');
    var fechaFin = document.getElementById('fechaFin');
    if (!fechaInicio || !fechaFin) return;

    function actualizarMinFin() {
        var valorInicio = fechaInicio.value;
        
        if (!valorInicio) {
            fechaFin.removeAttribute('min');
        } else {
            fechaFin.setAttribute('min', valorInicio);
        }

        // Revalidar si el campo ya tenía valor
        if (fechaFin.value !== '' && typeof window.revalidarCampo === 'function') {
            window.revalidarCampo(fechaFin);
        }
    }

    fechaInicio.addEventListener('change', actualizarMinFin);
    fechaInicio.addEventListener('input', actualizarMinFin);
    
    // Ejecutar al cargar (por si estamos editando)
    actualizarMinFin();
})();

// ============================================================
// TOAST DE PHP
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['error_toast'])): ?>
        if (typeof mostrarNotificacion === 'function') {
            mostrarNotificacion("<?= addslashes($_SESSION['error_toast']) ?>", 'danger', 'Error de Validación');
        }
        <?php unset($_SESSION['error_toast']); ?>
    <?php endif; ?>
});
</script>