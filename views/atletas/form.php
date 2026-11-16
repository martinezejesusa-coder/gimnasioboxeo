<?php
$atleta = $atleta ?? [];
$errores = $errores ?? [];
$documentosExistentes = $documentosExistentes ?? [];

$isEdit = !empty($atleta['id_atleta']);
$titulo = $isEdit ? 'Editar' : 'Nuevo';

function calcularEdadPHP($fechaNacimiento) {
    if (empty($fechaNacimiento)) return null;
    $fecha = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    return $hoy->diff($fecha)->y;
}

$fechaParaEdad = old('fecha_nacimiento', $atleta['fecha_nacimiento'] ?? '');
$edadCalculada = calcularEdadPHP($fechaParaEdad);
?>

<?= renderErroresGenerales($errores) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-arms-up"></i> <?= $titulo ?> Atleta</h2>
    <a href="?c=atletas&a=index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="?c=atletas&a=guardar" enctype="multipart/form-data" id="atletaForm" data-validate novalidate>
            <input type="hidden" name="id" value="<?= old('id', $atleta['id_atleta'] ?? '') ?>">
            
            <!-- SECCIÓN 1: INFORMACIÓN PERSONAL -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="pnombre" class="form-label required">Primer Nombre</label>
                            <input type="text" name="pnombre" id="pnombre" class="<?= fieldClass('pnombre', $errores) ?>" required 
                                   minlength="2" maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ ]+"
                                   value="<?= old('pnombre', $atleta['pnombre'] ?? '') ?>"
                                   placeholder="Ej: Juan"
                                   data-msg-required="El primer nombre es obligatorio"
                                   data-msg-pattern="Solo letras y espacios (sin números)"
                                   data-msg-minlength="Mínimo 2 caracteres"
                                   data-msg-valid="Nombre válido"
                                   data-hint="Solo letras, entre 2 y 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('pnombre', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="snombre" class="form-label">Segundo Nombre</label>
                            <input type="text" name="snombre" id="snombre" class="<?= fieldClass('snombre', $errores) ?>" 
                                   maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ ]+"
                                   value="<?= old('snombre', $atleta['snombre'] ?? '') ?>"
                                   placeholder="Ej: Carlos"
                                   data-msg-pattern="Solo letras y espacios"
                                   data-msg-valid="Nombre válido"
                                   data-hint="Opcional. Solo letras, máximo 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('snombre', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="papellido" class="form-label required">Primer Apellido</label>
                            <input type="text" name="papellido" id="papellido" class="<?= fieldClass('papellido', $errores) ?>" required 
                                   minlength="2" maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ ]+"
                                   value="<?= old('papellido', $atleta['papellido'] ?? '') ?>"
                                   placeholder="Ej: Pérez"
                                   data-msg-required="El primer apellido es obligatorio"
                                   data-msg-pattern="Solo letras y espacios"
                                   data-msg-minlength="Mínimo 2 caracteres"
                                   data-msg-valid="Apellido válido"
                                   data-hint="Solo letras, entre 2 y 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('papellido', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="sapellido" class="form-label">Segundo Apellido</label>
                            <input type="text" name="sapellido" id="sapellido" class="<?= fieldClass('sapellido', $errores) ?>" 
                                   maxlength="50"
                                   pattern="[a-zA-ZáéíóúñÑÁÉÍÓÚ ]+"
                                   value="<?= old('sapellido', $atleta['sapellido'] ?? '') ?>"
                                   placeholder="Ej: González"
                                   data-msg-pattern="Solo letras y espacios"
                                   data-msg-valid="Apellido válido"
                                   data-hint="Opcional. Solo letras, máximo 50 caracteres">
                            <div class="invalid-feedback d-block"><?= errorMsg('sapellido', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="cedula" class="form-label required">Cédula</label>
                            <input type="text" name="cedula" id="cedula" class="<?= fieldClass('cedula', $errores) ?>" required 
                                   maxlength="15"
                                   pattern="[VEJPvejp]?-?[0-9]{6,9}"
                                   value="<?= old('cedula', $atleta['cedula'] ?? '') ?>"
                                   placeholder="V-12345678"
                                   data-msg-required="La cédula es obligatoria"
                                   data-msg-pattern="Formato inválido. Ejemplos: V-12345678, E-12345678, 12345678"
                                   data-msg-valid="Cédula válida"
                                   data-hint="V- (venezolano), E- (extranjero), J- (jurídico), P- (pasaporte) o solo números">
                            <div class="invalid-feedback d-block"><?= errorMsg('cedula', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="fechaNacimientoInput" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fechaNacimientoInput" class="<?= fieldClass('fecha_nacimiento', $errores) ?>" 
                                   value="<?= old('fecha_nacimiento', $atleta['fecha_nacimiento'] ?? '') ?>"
                                   max="<?= date('Y-m-d', strtotime('-5 years')) ?>"
                                   min="<?= date('Y-m-d', strtotime('-100 years')) ?>"
                                   data-msg-valid="Fecha válida"
                                   data-hint="El atleta debe tener al menos 5 años">
                            <div class="invalid-feedback d-block"><?= errorMsg('fecha_nacimiento', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Edad</label>
                            <input type="text" class="form-control" readonly 
                                   id="edadInput"
                                   value="<?= $edadCalculada ? $edadCalculada . ' años' : 'No disponible' ?>">
                            <small class="text-muted">Calculada automáticamente</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN 2: ASOCIACIONES -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Asociaciones</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_club" class="form-label">Club</label>
                            <select name="id_club" id="id_club" class="<?= fieldClassSelect('id_club', $errores) ?>">
                                <option value="">-- Seleccionar club --</option>
                                <?php $clubSeleccionado = old('id_club', $atleta['id_club'] ?? ''); foreach($clubes as $c): ?>
                                    <option value="<?= htmlspecialchars($c['id_club']) ?>" <?= ($clubSeleccionado == $c['id_club']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_club']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback d-block"><?= errorMsg('id_club', $errores) ?></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select name="id_categoria" id="id_categoria" class="<?= fieldClassSelect('id_categoria', $errores) ?>">
                                <option value="">-- Seleccionar categoría --</option>
                                <?php $catSeleccionada = old('id_categoria', $atleta['id_categoria'] ?? ''); foreach($categorias as $c): ?>
                                    <option value="<?= htmlspecialchars($c['id_categoria']) ?>" <?= ($catSeleccionada == $c['id_categoria']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_categoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback d-block"><?= errorMsg('id_categoria', $errores) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN 3: NIVEL (SOLO LECTURA, CALCULADO) -->
            <?php if ($isEdit): ?>
            <div class="card mb-4 bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Nivel del Atleta (Calculado)</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="mb-0">
                                <span class="badge bg-<?= 
                                    ($atleta['nivel_calculado'] ?? 'Novato') == 'Novato' ? 'secondary' :
                                    (($atleta['nivel_calculado'] ?? '') == 'Intermedio' ? 'primary' :
                                    (($atleta['nivel_calculado'] ?? '') == 'Avanzado' ? 'warning' : 'danger')) ?>"
                                    style="font-size: 1.5rem;">
                                    <?= htmlspecialchars($atleta['nivel_calculado'] ?? 'Novato') ?>
                                </span>
                                <small class="text-muted ms-2">(<?= $atleta['total_combates'] ?? 0 ?> combates)</small>
                            </h3>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                El nivel se calcula automáticamente:
                                <ul class="mt-2 mb-0">
                                    <li><strong>Novato:</strong> 0 - 5 combates</li>
                                    <li><strong>Intermedio:</strong> 6 - 20 combates</li>
                                    <li><strong>Avanzado:</strong> 21 - 40 combates</li>
                                    <li><strong>Experto:</strong> 41+ combates</li>
                                </ul>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- SECCIÓN 4: FOTO -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-camera"></i> Foto de Perfil</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fotoInput" class="form-label">Seleccionar foto</label>
                            <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" id="fotoInput">
                            <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP. Máx 5MB</small>
                            <?php if (!empty($errores['foto'])): ?>
                                <div class="invalid-feedback d-block"><?= htmlspecialchars($errores['foto']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($atleta['foto'])): ?>
                                <div class="text-center">
                                    <label class="form-label">Foto actual</label>
                                    <div>
                                        <img src="<?= BASE_URL . htmlspecialchars($atleta['foto']) ?>" 
                                             alt="Foto actual" class="img-thumbnail"
                                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="fotoPreview" class="mt-3 text-center" style="display: none;">
                        <label class="form-label">Vista previa</label>
                        <div><img id="fotoPreviewImg" src="#" alt="Vista previa" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;"></div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN 5: DOCUMENTOS -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-paperclip"></i> Documentos</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Subir nuevos documentos</label>
                        <div id="dropZone" class="border rounded-3 p-4 text-center bg-light" style="cursor: pointer; transition: all 0.3s;">
                            <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-2">Arrastra y suelta tus documentos aquí</h5>
                            <p class="text-muted mb-0">o haz clic para seleccionar archivos</p>
                            <small class="text-muted">Formatos: PDF, JPG, PNG, DOC, DOCX (Max 5MB por archivo)</small>
                            <input type="file" name="documentos[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="d-none" id="documentosInput">
                        </div>
                        <div id="listaDocumentos" class="mt-3"></div>
                    </div>
                    
                    <?php if ($isEdit && !empty($documentosExistentes)): ?>
                        <div class="mt-4">
                            <label class="form-label fw-bold">Documentos actuales</label>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr><th>Nombre</th><th>Fecha</th><th>Acciones</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($documentosExistentes as $doc): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($doc['nombre_documento']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($doc['fecha_subida'])) ?></td>
                                                <td>
                                                    <a href="<?= BASE_URL . htmlspecialchars($doc['ruta_archivo']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                                    <a href="<?= BASE_URL . htmlspecialchars($doc['ruta_archivo']) ?>" download class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- BOTONES -->
            <div class="d-flex gap-2 justify-content-end">
                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</button>
                <a href="?c=atletas&a=index" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary" disabled>
                    <i class="bi bi-save"></i> Guardar Atleta
                </button>
            </div>
        </form>
    </div>
</div>

<style>
#dropZone.drag-over { background-color: #e7f5ff !important; border-color: #0d6efd !important; }
</style>

<script>
// ==================== CÁLCULO DE EDAD ====================
function calcularEdadJS(fechaNacimiento) {
    if (!fechaNacimiento) return null;
    const hoy = new Date();
    const fecha = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - fecha.getFullYear();
    const mes = hoy.getMonth() - fecha.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) edad--;
    return edad;
}

const fechaInput = document.getElementById('fechaNacimientoInput');
const edadInput = document.getElementById('edadInput');
if (fechaInput && edadInput) {
    fechaInput.addEventListener('change', function() {
        const fecha = this.value;
        edadInput.value = fecha ? (calcularEdadJS(fecha) + ' años') : 'No disponible';
    });
}

// ==================== PREVISUALIZACIÓN DE FOTO ====================
document.getElementById('fotoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('fotoPreview');
    const previewImg = document.getElementById('fotoPreviewImg');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) { previewImg.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else { preview.style.display = 'none'; }
});

// ==================== DRAG & DROP DOCUMENTOS ====================
(function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('documentosInput');
    const listaContainer = document.getElementById('listaDocumentos');
    if (!dropZone || !fileInput) return;
    
    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        agregarArchivos(Array.from(e.dataTransfer.files));
    });
    fileInput.addEventListener('change', () => agregarArchivos(Array.from(fileInput.files)));
    
    function agregarArchivos(nuevos) {
        const existentes = Array.from(fileInput.files);
        const dt = new DataTransfer();
        existentes.forEach(f => dt.items.add(f));
        nuevos.forEach(n => {
            if (!existentes.some(e => e.name === n.name && e.size === n.size)) dt.items.add(n);
        });
        fileInput.files = dt.files;
        actualizarLista();
    }
    
    window.removerArchivo = function(index) {
        const dt = new DataTransfer();
        const archivos = Array.from(fileInput.files);
        archivos.splice(index, 1);
        archivos.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
        actualizarLista();
    };
    
    function actualizarLista() {
        const archivos = Array.from(fileInput.files);
        if (!archivos.length) { listaContainer.innerHTML = ''; return; }
        let html = '<div class="list-group">';
        archivos.forEach((a, i) => {
            const kb = (a.size / 1024).toFixed(2);
            html += `<div class="list-group-item d-flex justify-content-between align-items-center">
                <div><i class="bi bi-file-earmark me-2"></i><strong>${escapeHtml(a.name)}</strong> <span class="text-muted">(${kb} KB)</span></div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removerArchivo(${i})"><i class="bi bi-trash"></i></button>
            </div>`;
        });
        html += '</div>';
        listaContainer.innerHTML = html;
    }
    
    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }
})();

// ==================== TOASTS PHP ====================
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['error_toast'])): ?>
        if (typeof mostrarNotificacion === 'function') {
            mostrarNotificacion("<?= addslashes($_SESSION['error_toast']) ?>", 'danger', 'Error de Validación');
        }
        <?php unset($_SESSION['error_toast']); ?>
    <?php endif; ?>
});
</script>