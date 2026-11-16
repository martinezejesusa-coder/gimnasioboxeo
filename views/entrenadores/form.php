<?php
$entrenador = $entrenador ?? [];
$errores = $errores ?? [];
$clubesAsignados = $clubesAsignados ?? [];
$paises = $paises ?? [];
$isEdit = !empty($entrenador['id_entrenador']);
$titulo = $isEdit ? 'Editar' : 'Nuevo';
?>

<?= renderErroresGenerales($errores) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-badge"></i> <?= $titulo ?> Entrenador</h2>
    <a href="?c=entrenadores&a=index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="?c=entrenadores&a=guardar" id="entrenadorForm" data-validate novalidate>
            <input type="hidden" name="id" value="<?= old('id', $entrenador['id_entrenador'] ?? '') ?>">
            
            <!-- SECCIÓN 1: INFORMACIÓN PERSONAL -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="pnombre_entrenador" class="form-label required">Primer Nombre</label>
                            <input type="text" name="pnombre_entrenador" id="pnombre_entrenador" class="<?= fieldClass('pnombre_entrenador', $errores) ?>" required 
                                   minlength="2" maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ\s'\.\-]+"
                                   value="<?= old('pnombre_entrenador', $entrenador['pnombre_entrenador'] ?? '') ?>"
                                   placeholder="Ej: Juan"
                                   data-msg-required="El primer nombre es obligatorio"
                                   data-msg-minlength="Mínimo 2 caracteres"
                                   data-msg-pattern="Solo letras, espacios, apóstrofes, puntos y guiones"
                                   data-msg-valid="Nombre válido"
                                   data-hint="Solo letras, entre 2 y 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('pnombre_entrenador', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="snombre_entrenador" class="form-label">Segundo Nombre</label>
                            <input type="text" name="snombre_entrenador" id="snombre_entrenador" class="<?= fieldClass('snombre_entrenador', $errores) ?>" 
                                   maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ\s'\.\-]+"
                                   value="<?= old('snombre_entrenador', $entrenador['snombre_entrenador'] ?? '') ?>"
                                   placeholder="Ej: Carlos"
                                   data-msg-pattern="Solo letras y espacios"
                                   data-msg-valid="Nombre válido"
                                   data-hint="Opcional. Solo letras, máximo 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('snombre_entrenador', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="apellido_entrenador" class="form-label required">Primer Apellido</label>
                            <input type="text" name="apellido_entrenador" id="apellido_entrenador" class="<?= fieldClass('apellido_entrenador', $errores) ?>" required 
                                   minlength="2" maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ\s'\.\-]+"
                                   value="<?= old('apellido_entrenador', $entrenador['apellido_entrenador'] ?? '') ?>"
                                   placeholder="Ej: Pérez"
                                   data-msg-required="El primer apellido es obligatorio"
                                   data-msg-minlength="Mínimo 2 caracteres"
                                   data-msg-pattern="Solo letras, espacios, apóstrofes, puntos y guiones"
                                   data-msg-valid="Apellido válido"
                                   data-hint="Solo letras, entre 2 y 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('apellido_entrenador', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="sapellido_entrenador" class="form-label">Segundo Apellido</label>
                            <input type="text" name="sapellido_entrenador" id="sapellido_entrenador" class="<?= fieldClass('sapellido_entrenador', $errores) ?>" 
                                   maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ\s'\.\-]+"
                                   value="<?= old('sapellido_entrenador', $entrenador['sapellido_entrenador'] ?? '') ?>"
                                   placeholder="Ej: González"
                                   data-msg-pattern="Solo letras y espacios"
                                   data-msg-valid="Apellido válido"
                                   data-hint="Opcional. Solo letras, máximo 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('sapellido_entrenador', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="cedula_entrenador" class="form-label required">Cédula</label>
                            <input type="text" name="cedula_entrenador" id="cedula_entrenador" class="<?= fieldClass('cedula_entrenador', $errores) ?>" required 
                                   maxlength="15"
                                   pattern="[VEJPvejp]?-?[0-9]{6,9}"
                                   value="<?= old('cedula_entrenador', $entrenador['cedula_entrenador'] ?? '') ?>"
                                   placeholder="V-12345678"
                                   data-msg-required="La cédula es obligatoria"
                                   data-msg-pattern="Formato inválido. Ejemplos: V-12345678, E-12345678, 12345678"
                                   data-msg-valid="Cédula válida"
                                   data-hint="V- (venezolano), E- (extranjero), J- (jurídico), P- (pasaporte) o solo números">
                            <div class="invalid-feedback d-block"><?= errorMsg('cedula_entrenador', $errores) ?></div>
                        </div>
                        
                        <!-- Teléfono -->
                        <div class="col-md-4">
                            <label for="inputTelefono" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <select id="selectPais" class="form-select" style="max-width: 130px;">
                                    <option value="">🌐 País</option>
                                    <?php 
                                    $paisPreferido = $_SESSION['pais_preferido'] ?? 'VE';
                                    foreach($paises as $code => $info): ?>
                                        <option value="<?= $code ?>" data-codigo="<?= $info['codigo'] ?>"
                                            <?= ($paisPreferido === $code) ? 'selected' : '' ?>>
                                            <?= $info['bandera'] ?> <?= $info['codigo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="tel" name="telefono" id="inputTelefono" class="<?= fieldClass('telefono', $errores) ?>" 
                                       maxlength="25"
                                       pattern="\+?[0-9\s\-\(\)\.]+"
                                       value="<?= old('telefono', $entrenador['telefono'] ?? '') ?>"
                                       placeholder="+58 412-1234567"
                                       data-msg-pattern="Solo números, +, -, (), espacios y puntos"
                                       data-msg-valid="Teléfono válido"
                                       data-hint="Acepta formato nacional e internacional">
                            </div>
                            <div class="invalid-feedback d-block"><?= errorMsg('telefono', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" id="correo" class="<?= fieldClass('correo', $errores) ?>" 
                                   maxlength="100"
                                   value="<?= old('correo', $entrenador['correo'] ?? '') ?>"
                                   placeholder="entrenador@ejemplo.com"
                                   data-msg-email="Ingresa un correo válido (ejemplo@dominio.com)"
                                   data-msg-valid="Correo válido"
                                   data-hint="Correo de contacto del entrenador">
                            <div class="invalid-feedback d-block"><?= errorMsg('correo', $errores) ?></div>
                        </div>
                        
                        <!-- Estado activo -->
                        <div class="col-md-12">
                            <label class="form-label d-block">Estado</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="activo" class="form-check-input" id="activoCheck" 
                                       <?= (!isset($entrenador['activo']) || !empty($entrenador['activo'])) ? 'checked' : '' ?>>
                                <label for="activoCheck" class="form-check-label">
                                    <i class="bi bi-check-circle-fill text-success"></i> Entrenador activo
                                </label>
                                <small class="text-muted d-block">Los entrenadores inactivos no se asignan a clubes nuevos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN 2: CLUBES ASIGNADOS (CON SELECT2 MÚLTIPLE) -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Clubes Asignados</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="clubes" class="form-label">Selecciona los clubes donde trabaja este entrenador</label>
                            <?php 
                            $clubesSeleccionados = old('clubes', $clubesAsignados ?? []);
                            if (!is_array($clubesSeleccionados)) $clubesSeleccionados = [];
                            ?>
                            <select name="clubes[]" id="clubes" multiple class="form-select" size="6" style="min-height: 150px;">
                                <?php foreach($clubes as $c): ?>
                                    <option value="<?= htmlspecialchars($c['id_club']) ?>" 
                                        <?= in_array($c['id_club'], $clubesSeleccionados) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_club']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle"></i> 
                                Busca y selecciona los clubes donde trabaja este entrenador
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- BOTONES -->
            <div class="d-flex gap-2 justify-content-end">
                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</button>
                <a href="?c=entrenadores&a=index" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary" disabled>
                    <i class="bi bi-save"></i> Guardar Entrenador
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.select2-selection.is-invalid { border-color: #dc3545 !important; box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important; }
.select2-selection.is-valid { border-color: #198754 !important; box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important; }
</style>

<script>
// ============================================================
// TELÉFONO CON CÓDIGO DE PAÍS
// ============================================================
var selectPais = document.getElementById('selectPais');
var inputTelefono = document.getElementById('inputTelefono');

(function detectarPaisInicial() {
    if (!selectPais || !inputTelefono) return;
    var tel = (inputTelefono.value || '').trim();
    if (!tel) return;
    var match = tel.match(/^\+(\d+)/);
    if (!match) return;
    var codigoTel = '+' + match[1];
    var mejorOpcion = null, mejorLongitud = 0;
    Array.prototype.forEach.call(selectPais.options, function(opt) {
        var codigo = opt.dataset.codigo;
        if (!codigo) return;
        if (codigoTel.startsWith(codigo) && codigo.length > mejorLongitud) {
            mejorOpcion = opt;
            mejorLongitud = codigo.length;
        }
    });
    if (mejorOpcion) selectPais.value = mejorOpcion.value;
})();

if (selectPais) {
    selectPais.addEventListener('change', function() {
        var codigo = this.options[this.selectedIndex].dataset.codigo;
        if (!codigo) return;
        var valor = inputTelefono.value.trim().replace(/^\+\d+\s?/, '');
        inputTelefono.value = codigo + ' ' + valor;
        inputTelefono.focus();
    });
}

// ============================================================
// CONFIRMACIÓN DE LIMPIAR
// ============================================================
document.querySelector('button[type="reset"]')?.addEventListener('click', function(e) {
    if (!confirm('¿Estás seguro de que deseas limpiar todos los campos del formulario?')) {
        e.preventDefault();
    }
});

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

// ============================================================
// SELECT2 PARA CLUBES (MÚLTIPLE)
// ============================================================
window.addEventListener('load', function() {
    var $ = window.jQuery;
    if (!$ || !$.fn || typeof $.fn.select2 === 'undefined') {
        console.warn('⚠️ Select2 no disponible');
        return;
    }

    var selectClubes = document.getElementById('clubes');
    if (!selectClubes) return;

    $(selectClubes).select2({
        theme: 'bootstrap-5',
        width: '100%',
        language: 'es',
        placeholder: '🔍 Busca y selecciona clubes...',
        allowClear: true,
        closeOnSelect: false
    });

    console.log('✅ Select2 múltiple inicializado para clubes');
});
</script>