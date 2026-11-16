<?php
$juez = $juez ?? [];
$errores = $errores ?? [];
$isEdit = !empty($juez['id_juez']);
$titulo = $isEdit ? 'Editar' : 'Nuevo';
?>

<?= renderErroresGenerales($errores) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-check"></i> <?= $titulo ?> Juez</h2>
    <a href="?c=jueces&a=index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="?c=jueces&a=guardar" data-validate novalidate>
            <input type="hidden" name="id" value="<?= old('id', $juez['id_juez'] ?? '') ?>">
            
            <div class="row g-3">
                <!-- Primer Nombre -->
                <div class="col-md-6">
                    <label for="pnombre" class="form-label required">Primer Nombre</label>
                    <input type="text" name="pnombre" id="pnombre" class="<?= fieldClass('pnombre', $errores) ?>" required 
                           minlength="2" maxlength="50"
                           pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ ]+"
                           value="<?= old('pnombre', $juez['pnombre'] ?? '') ?>"
                           placeholder="Ej: Carlos"
                           data-msg-required="El nombre del juez es obligatorio"
                           data-msg-pattern="Solo letras y espacios"
                           data-msg-minlength="Mínimo 2 caracteres"
                           data-msg-valid="Nombre válido"
                           data-hint="Solo letras, entre 2 y 50 caracteres">
                    <div class="invalid-feedback d-block"><?= errorMsg('pnombre', $errores) ?></div>
                </div>
                
                <!-- Apellido -->
                <div class="col-md-6">
                    <label for="papellido" class="form-label required">Apellido</label>
                    <input type="text" name="papellido" id="papellido" class="<?= fieldClass('papellido', $errores) ?>" required 
                           minlength="2" maxlength="50"
                           pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ ]+"
                           value="<?= old('papellido', $juez['papellido'] ?? '') ?>"
                           placeholder="Ej: Pérez"
                           data-msg-required="El apellido del juez es obligatorio"
                           data-msg-pattern="Solo letras y espacios"
                           data-msg-minlength="Mínimo 2 caracteres"
                           data-msg-valid="Apellido válido"
                           data-hint="Solo letras, entre 2 y 50 caracteres">
                    <div class="invalid-feedback d-block"><?= errorMsg('papellido', $errores) ?></div>
                </div>
                
                <!-- Cédula (opcional) -->
                <div class="col-md-6">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" name="cedula" id="cedula" class="<?= fieldClass('cedula', $errores) ?>" 
                           maxlength="15"
                           pattern="[VEJPvejp]?-?[0-9]{6,9}"
                           value="<?= old('cedula', $juez['cedula'] ?? '') ?>"
                           placeholder="V-12345678"
                           data-msg-pattern="Formato inválido. Use V-, E-, J-, P- seguido de 6 a 9 dígitos, o solo números"
                           data-msg-valid="Cédula válida"
                           data-hint="V- (venezolano), E- (extranjero), J- (jurídico), P- (pasaporte) o solo números">
                    <div class="invalid-feedback d-block"><?= errorMsg('cedula', $errores) ?></div>
                </div>
                
                <!-- Estado -->
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="activo" class="form-check-input" id="activoCheck" 
                               <?= (empty($juez) || !empty($juez['activo'])) ? 'checked' : '' ?>>
                        <label for="activoCheck" class="form-check-label">
                            <i class="bi bi-check-circle-fill text-success"></i> Juez activo
                        </label>
                        <small class="text-muted d-block">Los jueces inactivos no aparecerán en los combates</small>
                    </div>
                </div>
            </div>
            
            <!-- Información adicional (solo en edición) -->
            <?php if ($isEdit): ?>
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                <strong>ID:</strong> <?= $juez['id_juez'] ?>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-clipboard-data"></i>
                                <strong>Tarjetas de puntuación:</strong> 
                                <?php
                                try {
                                    $stmt = (new Juez())->getDb()->prepare("SELECT COUNT(*) FROM tarjetas_puntuacion WHERE id_juez = ?");
                                    $stmt->execute([$juez['id_juez']]);
                                    echo (int)$stmt->fetchColumn();
                                } catch (Exception $e) { echo '0'; }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary" disabled>
                    <i class="bi bi-save"></i> Guardar
                </button>
                <a href="?c=jueces&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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