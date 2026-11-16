<?php
$club = $club ?? [];
$errores = $errores ?? [];
$estados = $estados ?? [];
$entrenadorActual = $entrenadorActual ?? null;
$isEdit = !empty($club['id_club']);
$titulo = $isEdit ? 'Editar' : 'Nuevo';
?>

<?= renderErroresGenerales($errores) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-building"></i> <?= $titulo ?> Club</h2>
    <a href="?c=clubes&a=index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form id="formClub" method="POST" action="?c=clubes&a=guardar" enctype="multipart/form-data" data-validate novalidate>
            <input type="hidden" name="id" value="<?= old('id', $club['id_club'] ?? '') ?>">
            
            <div class="row g-3">
                <!-- Nombre del club -->
                <div class="col-md-12">
                    <label for="nombre_club" class="form-label required">Nombre del club</label>
                    <input type="text" name="nombre_club" id="nombre_club" class="<?= fieldClass('nombre_club', $errores) ?>" required 
                           minlength="3" maxlength="100"
                           pattern="[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ \-.,&]+"
                           value="<?= old('nombre_club', $club['nombre_club'] ?? '') ?>"
                           placeholder="Ej: Gimnasio Boxeo Elite"
                           data-msg-required="El nombre del club es obligatorio"
                           data-msg-minlength="Mínimo 3 caracteres"
                           data-msg-pattern="Solo letras, números, espacios, guiones, puntos, comas y &"
                           data-msg-valid="Nombre válido"
                           data-hint="Entre 3 y 100 caracteres. Debe ser único.">
                    <div class="invalid-feedback d-block"><?= errorMsg('nombre_club', $errores) ?></div>
                </div>
                
                <!-- Entrenador responsable -->
                <div class="col-md-6">
                    <label for="id_entrenador" class="form-label">Entrenador responsable</label>
                    <select name="id_entrenador" id="id_entrenador" class="<?= fieldClassSelect('id_entrenador', $errores) ?>">
                        <option value="">-- Ninguno --</option>
                        <?php $entSel = old('id_entrenador', $entrenadorActual ?? ''); foreach($entrenadores as $e): ?>
                            <option value="<?= $e['id_entrenador'] ?>" <?= ($entSel == $e['id_entrenador']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['pnombre_entrenador'] . ' ' . $e['apellido_entrenador']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Estado -->
                <div class="col-md-4">
                    <label for="selectEstado" class="form-label">Estado</label>
                    <select name="id_estado" id="selectEstado" class="<?= fieldClassSelect('id_estado', $errores) ?>">
                        <option value="">-- Seleccionar estado --</option>
                        <?php $estSel = old('id_estado', $club['id_estado'] ?? ''); foreach($estados as $est): ?>
                            <option value="<?= $est['id_estado'] ?>" <?= ($estSel == $est['id_estado']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($est['nombre_estado']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback d-block"><?= errorMsg('id_estado', $errores) ?></div>
                </div>

                <!-- Municipio -->
                <div class="col-md-4">
                    <label for="selectMunicipio" class="form-label">Municipio</label>
                    <select name="id_municipio" id="selectMunicipio" class="<?= fieldClassSelect('id_municipio', $errores) ?>">
                        <option value="">-- Seleccionar municipio --</option>
                    </select>
                    <div class="invalid-feedback d-block"><?= errorMsg('id_municipio', $errores) ?></div>
                </div>

                <!-- Parroquia -->
                <div class="col-md-4">
                    <label for="selectParroquia" class="form-label">Parroquia</label>
                    <select name="id_parroquia" id="selectParroquia" class="<?= fieldClassSelect('id_parroquia', $errores) ?>">
                        <option value="">-- Seleccionar parroquia --</option>
                    </select>
                    <div class="invalid-feedback d-block"><?= errorMsg('id_parroquia', $errores) ?></div>
                </div>
                                
                <!-- Dirección exacta -->
                <div class="col-md-12">
                    <label for="ubicacion" class="form-label">Dirección exacta</label>
                    <input type="text" name="ubicacion" id="ubicacion" class="<?= fieldClass('ubicacion', $errores) ?>" 
                           maxlength="255"
                           pattern="[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ \-.,#°ºª]+"
                           value="<?= old('ubicacion', $club['ubicacion'] ?? '') ?>"
                           placeholder="Ej: Av. Principal de Las Mercedes, Edificio Ring Central, Piso 2"
                           data-msg-pattern="Solo letras, números, #, °, º, ª, puntos y comas"
                           data-msg-valid="Dirección válida"
                           data-hint="Dirección exacta (calle, número, edificio, piso, etc.)">
                    <div class="invalid-feedback d-block"><?= errorMsg('ubicacion', $errores) ?></div>
                </div>
                
                <!-- Teléfono -->
                <div class="col-md-6">
                    <label for="inputTelefono" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <select id="selectPais" class="form-select" style="max-width: 180px;">
                            <option value="">🌐 País</option>
                            <?php 
                            $paisPreferido = $_SESSION['pais_preferido'] ?? 'VE';
                            foreach($paises as $code => $info): ?>
                                <option value="<?= $code ?>" data-codigo="<?= $info['codigo'] ?>"
                                    <?= ($paisPreferido === $code) ? 'selected' : '' ?>>
                                    <?= $info['bandera'] ?> <?= htmlspecialchars($info['nombre']) ?> (<?= $info['codigo'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="tel" name="telefono" id="inputTelefono" class="<?= fieldClass('telefono', $errores) ?>" 
                               maxlength="25"
                               pattern="\+?[0-9 \-\(\)\.]+"
                               value="<?= old('telefono', $club['telefono'] ?? '') ?>"
                               placeholder="+58 412-1234567"
                               data-msg-pattern="Solo números, +, -, (), espacios y puntos"
                               data-msg-valid="Teléfono válido"
                               data-hint="Acepta formato nacional e internacional">
                    </div>
                    <div class="invalid-feedback d-block"><?= errorMsg('telefono', $errores) ?></div>
                </div>
                
                <!-- Correo -->
                <div class="col-md-6">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" id="correo" class="<?= fieldClass('correo', $errores) ?>" 
                           maxlength="100"
                           value="<?= old('correo', $club['correo'] ?? '') ?>"
                           placeholder="club@ejemplo.com"
                           data-msg-email="Ingresa un correo válido (ejemplo@dominio.com)"
                           data-msg-valid="Correo válido"
                           data-hint="Correo de contacto del club">
                    <div class="invalid-feedback d-block"><?= errorMsg('correo', $errores) ?></div>
                </div>
                
                <!-- Logo -->
                <div class="col-md-12">
                    <label for="logoInput" class="form-label">Logo del club</label>
                    <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" id="logoInput">
                    <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP. Máx 2MB</small>
                    <?php if (!empty($errores['logo'])): ?>
                        <div class="invalid-feedback d-block"><?= htmlspecialchars($errores['logo']) ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($club['logo'])): ?>
                        <div class="mt-3" id="logoActual">
                            <img src="<?= BASE_URL . htmlspecialchars($club['logo']) ?>" 
                                 alt="Logo actual" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                            <small class="text-muted d-block">Logo actual</small>
                        </div>
                    <?php endif; ?>
                    
                    <div id="logoPreview" class="mt-3" style="display: none;">
                        <img id="previewImg" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                        <small class="text-muted d-block">Nuevo logo</small>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary" disabled>
                    <i class="bi bi-save"></i> Guardar
                </button>
                <a href="?c=clubes&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================================
// GEOGRAFÍA VENEZUELA (AJAX CASCADA)
// ============================================================
const selectEstado = document.getElementById('selectEstado');
const selectMunicipio = document.getElementById('selectMunicipio');
const selectParroquia = document.getElementById('selectParroquia');

const estadoActual = "<?= old('id_estado', $club['id_estado'] ?? '') ?>";
const municipioActual = "<?= old('id_municipio', $club['id_municipio'] ?? '') ?>";
const parroquiaActual = "<?= old('id_parroquia', $club['id_parroquia'] ?? '') ?>";

async function cargarMunicipios(idEstado, idMunicipioSeleccionado = null) {
    selectMunicipio.innerHTML = '<option value="">-- Seleccionar municipio --</option>';
    selectParroquia.innerHTML = '<option value="">-- Seleccionar parroquia --</option>';
    selectParroquia.disabled = true;
    
    if (!idEstado) { selectMunicipio.disabled = true; return; }
    
    try {
        const res = await fetch(`?c=clubes&a=municipiosAjax&id_estado=${idEstado}`);
        const municipios = await res.json();
        
        selectMunicipio.disabled = false;
        municipios.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.id_municipio;
            opt.textContent = m.nombre_municipio;
            if (idMunicipioSeleccionado && idMunicipioSeleccionado == m.id_municipio) opt.selected = true;
            selectMunicipio.appendChild(opt);
        });
        
        if (idMunicipioSeleccionado) cargarParroquias(idMunicipioSeleccionado, parroquiaActual);
    } catch (e) { console.error('Error cargando municipios:', e); }
}

async function cargarParroquias(idMunicipio, idParroquiaSeleccionada = null) {
    selectParroquia.innerHTML = '<option value="">-- Seleccionar parroquia --</option>';
    if (!idMunicipio) { selectParroquia.disabled = true; return; }

    try {
        const res = await fetch(`?c=clubes&a=parroquiasAjax&id_municipio=${idMunicipio}`);
        const parroquias = await res.json();
        
        selectParroquia.disabled = false;
        parroquias.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id_parroquia;
            opt.textContent = p.nombre_parroquia;
            if (idParroquiaSeleccionada && idParroquiaSeleccionada == p.id_parroquia) opt.selected = true;
            selectParroquia.appendChild(opt);
        });
    } catch (e) { console.error('Error cargando parroquias:', e); }
}

selectEstado?.addEventListener('change', function() { cargarMunicipios(this.value); });
selectMunicipio?.addEventListener('change', function() { cargarParroquias(this.value); });

if (estadoActual) { cargarMunicipios(estadoActual, municipioActual); }

// ============================================================
// TELÉFONO CON CÓDIGO DE PAÍS AUTOMÁTICO
// ============================================================
const selectPais = document.getElementById('selectPais');
const inputTelefono = document.getElementById('inputTelefono');

(function detectarPaisInicial() {
    if (!selectPais || !inputTelefono) return;
    const tel = (inputTelefono.value || '').trim();
    if (!tel) return;
    const match = tel.match(/^\+(\d+)/);
    if (!match) return;
    const codigoTel = '+' + match[1];
    let mejorOpcion = null;
    let mejorLongitud = 0;
    Array.from(selectPais.options).forEach(opt => {
        const codigo = opt.dataset.codigo;
        if (!codigo) return;
        if (codigoTel.startsWith(codigo) && codigo.length > mejorLongitud) {
            mejorOpcion = opt;
            mejorLongitud = codigo.length;
        }
    });
    if (mejorOpcion) selectPais.value = mejorOpcion.value;
})();

selectPais?.addEventListener('change', function() {
    const codigo = this.options[this.selectedIndex].dataset.codigo;
    if (!codigo) return;
    let valor = inputTelefono.value.trim().replace(/^\+\d+\s?/, '');
    inputTelefono.value = codigo + ' ' + valor;
    inputTelefono.focus();
});

// ============================================================
// PREVISUALIZACIÓN DE LOGO
// ============================================================
const logoInput = document.getElementById('logoInput');
const logoPreview = document.getElementById('logoPreview');
const previewImg = document.getElementById('previewImg');
const logoActual = document.getElementById('logoActual');

if (logoInput) {
    logoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                logoPreview.style.display = 'block';
                if (logoActual) logoActual.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            logoPreview.style.display = 'none';
            if (logoActual) logoActual.style.display = 'block';
        }
    });
}

// ============================================================
// TOASTS PHP (Éxito / Error de Validación Backend)
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['error_toast'])): ?>
        if (typeof mostrarNotificacion === 'function') {
            mostrarNotificacion("<?= addslashes($_SESSION['error_toast']) ?>", 'danger', 'Error de Validación');
        }
        <?php unset($_SESSION['error_toast']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['ok'])): ?>
        if (typeof mostrarNotificacion === 'function') {
            mostrarNotificacion("<?= addslashes($_SESSION['ok']) ?>", 'success', '¡Éxito!');
        }
        <?php unset($_SESSION['ok']); ?>
    <?php endif; ?>
});
</script>