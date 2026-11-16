<?php
$usuario = $usuario ?? [];
$errores = $errores ?? [];
$isEdit = !empty($usuario['id_usuario']);
$titulo = $isEdit ? 'Editar' : 'Nuevo';
$idActual = (int)($_SESSION['usuario']['id_usuario'] ?? 0);
$esMiUsuario = $isEdit && ((int)$usuario['id_usuario'] === $idActual);
?>

<?= renderErroresGenerales($errores) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-plus"></i> <?= $titulo ?> Usuario</h2>
    <a href="?c=usuarios&a=index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="?c=usuarios&a=guardar" data-validate novalidate id="usuarioForm">
            <input type="hidden" name="id" value="<?= old('id', $usuario['id_usuario'] ?? '') ?>">
            
            <div class="row g-3">
                <!-- Username -->
                <div class="col-md-12">
                    <label for="username" class="form-label required">Nombre de usuario</label>
                    <input type="text" name="username" id="username" class="<?= fieldClass('username', $errores) ?>" required 
                           minlength="3" maxlength="50"
                           pattern="[a-zA-Z0-9_]+"
                           value="<?= old('username', $usuario['username'] ?? '') ?>"
                           placeholder="Ej: admin"
                           autocomplete="off"
                           data-msg-required="El nombre de usuario es obligatorio"
                           data-msg-pattern="Solo letras, números y guión bajo"
                           data-msg-minlength="Mínimo 3 caracteres"
                           data-msg-valid="Usuario disponible"
                           data-hint="Entre 3 y 50 caracteres. Solo letras, números y _">
                    <div class="invalid-feedback d-block"><?= errorMsg('username', $errores) ?></div>
                </div>
                
                <!-- Contraseña -->
                <div class="col-md-6">
                    <label for="passwordInput" class="form-label <?= $isEdit ? '' : 'required' ?>">
                        Contraseña <?= $isEdit ? '<span class="text-muted small">(dejar vacío para mantener actual)</span>' : '' ?>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordInput" 
                               class="<?= fieldClass('password', $errores) ?>"
                               <?= $isEdit ? '' : 'required' ?>
                               minlength="8" maxlength="72"
                               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,72}"
                               placeholder="<?= $isEdit ? 'Nueva contraseña (opcional)' : 'Mínimo 8 caracteres' ?>"
                               autocomplete="new-password"
                               data-msg-required="La contraseña es obligatoria"
                               data-msg-minlength="Mínimo 8 caracteres"
                               data-msg-pattern="Mínimo 8 caracteres, 1 mayúscula, 1 minúscula y 1 número"
                               data-msg-valid="Contraseña válida"
                               data-hint="<?= $isEdit ? 'Dejar vacío para no cambiarla' : 'Mínimo 8 caracteres, 1 mayúscula, 1 minúscula y 1 número' ?>">
                        <button type="button" class="btn btn-outline-secondary" id="togglePass" tabindex="-1">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    
                    <div class="mt-2" id="strengthContainer" style="display:none;">
                        <div class="progress" style="height: 6px;">
                            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <small class="d-block mt-1" id="strengthText"></small>
                    </div>
                    
                    <ul class="list-unstyled mt-2 mb-0" id="reqList" style="display:none;">
                        <li class="small text-muted" data-req="len"><i class="bi bi-circle"></i> Mínimo 8 caracteres</li>
                        <li class="small text-muted" data-req="upper"><i class="bi bi-circle"></i> Al menos 1 mayúscula</li>
                        <li class="small text-muted" data-req="lower"><i class="bi bi-circle"></i> Al menos 1 minúscula</li>
                        <li class="small text-muted" data-req="num"><i class="bi bi-circle"></i> Al menos 1 número</li>
                    </ul>
                    
                    <div class="invalid-feedback d-block"><?= errorMsg('password', $errores) ?></div>
                </div>
                
                <!-- Confirmar contraseña -->
                <div class="col-md-6">
                    <label for="passwordConfirmInput" class="form-label <?= $isEdit ? '' : 'required' ?>">Confirmar contraseña</label>
                    <div class="input-group">
                        <input type="password" name="password_confirm" id="passwordConfirmInput" 
                               class="<?= fieldClass('password_confirm', $errores) ?>"
                               <?= $isEdit ? '' : 'required' ?>
                               minlength="8" maxlength="72"
                               data-same-value-as="passwordInput"
                               placeholder="Repite la contraseña"
                               autocomplete="new-password"
                               data-msg-required="Debes confirmar la contraseña"
                               data-msg-same-value-as="Las contraseñas no coinciden"
                               data-msg-valid="Las contraseñas coinciden"
                               data-hint="Escribe la misma contraseña para confirmar">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassConfirm" tabindex="-1">
                            <i class="bi bi-eye" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback d-block"><?= errorMsg('password_confirm', $errores) ?></div>
                </div>
                
                <!-- Rol -->
                <div class="col-md-6">
                    <label for="rol" class="form-label required">Rol</label>
                    <select name="rol" id="rol" class="<?= fieldClassSelect('rol', $errores) ?>" required
                            data-msg-required="El rol es obligatorio"
                            data-msg-valid="Rol válido"
                            data-hint="Administrador: acceso total | Entrenador: gestión deportiva | Consulta: solo lectura">
                        <?php $rolSel = old('rol', $usuario['rol'] ?? 'Consulta'); ?>
                        <option value="Administrador" <?= ($rolSel === 'Administrador') ? 'selected' : '' ?>>👑 Administrador</option>
                        <option value="Entrenador" <?= ($rolSel === 'Entrenador') ? 'selected' : '' ?>>🥊 Entrenador</option>
                        <option value="Consulta" <?= ($rolSel === 'Consulta') ? 'selected' : '' ?>>👁️ Consulta</option>
                    </select>
                    <div class="invalid-feedback d-block"><?= errorMsg('rol', $errores) ?></div>
                </div>
                
                <!-- Activo -->
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="activo" class="form-check-input" id="activoCheck" 
                               <?= (empty($usuario) || !empty($usuario['activo'])) ? 'checked' : '' ?>>
                        <label for="activoCheck" class="form-check-label">
                            <i class="bi bi-check-circle-fill text-success"></i> Usuario activo
                        </label>
                        <small class="text-muted d-block">Los usuarios inactivos no pueden iniciar sesión</small>
                    </div>
                    <?php if (!empty($errores['activo'])): ?>
                        <div class="invalid-feedback d-block"><?= htmlspecialchars($errores['activo']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($esMiUsuario): ?>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Estás editando tu propio usuario.</strong>
                    No puedes desactivarlo ni cambiarte a un rol menor si eres el único Administrador activo.
                </div>
            <?php endif; ?>
            
            <?php if ($isEdit): ?>
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                <strong>ID:</strong> <?= $usuario['id_usuario'] ?>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i>
                                Contraseña actual: <strong>••••••••••</strong> (oculta por seguridad)
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
                <a href="?c=usuarios&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================================
// TOGGLE VER/OCULTAR CONTRASEÑA
// ============================================================
function setupToggle(btnId, inputId, iconId) {
    var btn = document.getElementById(btnId);
    var input = document.getElementById(inputId);
    var icon = document.getElementById(iconId);
    if (!btn || !input || !icon) return;
    btn.addEventListener('click', function() {
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
}
setupToggle('togglePass', 'passwordInput', 'eyeIcon');
setupToggle('togglePassConfirm', 'passwordConfirmInput', 'eyeIconConfirm');

// ============================================================
// MEDIDOR DE FORTALEZA
// ============================================================
var passwordInput = document.getElementById('passwordInput');
var strengthContainer = document.getElementById('strengthContainer');
var strengthBar = document.getElementById('strengthBar');
var strengthText = document.getElementById('strengthText');
var reqList = document.getElementById('reqList');

function evaluarFortaleza(pass) {
    var checks = {
        len: pass.length >= 8,
        upper: /[A-Z]/.test(pass),
        lower: /[a-z]/.test(pass),
        num: /[0-9]/.test(pass),
        special: /[^A-Za-z0-9]/.test(pass),
        long: pass.length >= 12
    };
    var score = 0;
    if (checks.len) score++;
    if (checks.upper) score++;
    if (checks.lower) score++;
    if (checks.num) score++;
    if (checks.special) score++;
    if (checks.long) score++;
    return { score: score, checks: checks };
}

if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        var val = this.value;
        
        if (val.length === 0) {
            if (strengthContainer) strengthContainer.style.display = 'none';
            if (reqList) reqList.style.display = 'none';
            // En edición, si vacían el password, quitar required del confirm
            if (<?= $isEdit ? 'true' : 'false' ?> && passwordConfirmInput) {
                passwordConfirmInput.removeAttribute('required');
                passwordConfirmInput.value = '';
                passwordConfirmInput.classList.remove('is-valid', 'is-invalid');
                if (typeof window.revalidarCampo === 'function') window.revalidarCampo(passwordConfirmInput);
            }
            return;
        }
        
        if (strengthContainer) strengthContainer.style.display = 'block';
        if (reqList) reqList.style.display = 'block';
        
        // En edición, si escriben password, hacer required el confirm
        if (<?= $isEdit ? 'true' : 'false' ?> && passwordConfirmInput) {
            passwordConfirmInput.setAttribute('required', 'required');
        }
        
        var r = evaluarFortaleza(val);
        
        ['len', 'upper', 'lower', 'num'].forEach(function(req) {
            var li = reqList.querySelector('[data-req="' + req + '"]');
            if (!li) return;
            if (r.checks[req]) {
                li.className = 'small text-success';
                li.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + li.textContent.trim().replace(/^.\s*/, '');
            } else {
                li.className = 'small text-muted';
                li.innerHTML = '<i class="bi bi-circle"></i> ' + li.textContent.trim().replace(/^.\s*/, '');
            }
        });
        
        var pct, cls, txt;
        if (r.score <= 2)      { pct = 25;  cls = 'bg-danger';  txt = '🔴 Débil'; }
        else if (r.score === 3){ pct = 50;  cls = 'bg-warning'; txt = '🟡 Aceptable'; }
        else if (r.score === 4){ pct = 75;  cls = 'bg-info';    txt = '🔵 Buena'; }
        else if (r.score === 5){ pct = 90;  cls = 'bg-success'; txt = '🟢 Fuerte'; }
        else                   { pct = 100; cls = 'bg-success'; txt = '💪 Excelente'; }
        
        strengthBar.style.width = pct + '%';
        strengthBar.className = 'progress-bar ' + cls;
        strengthText.textContent = txt;
        strengthText.className = 'd-block mt-1 ' + (r.score <= 2 ? 'text-danger' : r.score <= 3 ? 'text-warning' : 'text-success');
    });
}

// ============================================================
// VALIDAR COINCIDENCIA DE CONTRASEÑAS
// (ahora usa data-same-value-as, pero forzamos revalidación)
// ============================================================
var passwordConfirmInput = document.getElementById('passwordConfirmInput');

if (passwordConfirmInput && passwordInput) {
    passwordConfirmInput.addEventListener('input', function() {
        if (typeof window.revalidarCampo === 'function') {
            window.revalidarCampo(passwordConfirmInput);
        }
    });
    
    passwordInput.addEventListener('input', function() {
        if (passwordConfirmInput.value !== '' && typeof window.revalidarCampo === 'function') {
            window.revalidarCampo(passwordConfirmInput);
        }
    });
}

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