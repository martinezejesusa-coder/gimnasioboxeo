<?php
$isEdit  = !empty($categoria['id_categoria']);
$titulo  = $isEdit ? 'Editar' : 'Nueva';
$errores = $errores ?? [];
?>

<?= renderErroresGenerales($errores) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-tags"></i> <?= $titulo ?> Categoría</h2>
    <a href="?c=categorias&a=index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="?c=categorias&a=guardar" data-validate novalidate>
            <input type="hidden" name="id" value="<?= old('id', $categoria['id_categoria'] ?? '') ?>">

            <div class="row g-3">

                <!-- Nombre -->
                <div class="col-md-12">
                    <label for="nombre_categoria" class="form-label required">Nombre de la Categoría</label>
                    <input type="text"
                           name="nombre_categoria"
                           id="nombre_categoria"
                           class="<?= fieldClass('nombre_categoria', $errores) ?>"
                           required
                           minlength="2"
                           maxlength="50"
                           pattern="[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ \-\.]+"
                           value="<?= old('nombre_categoria', $categoria['nombre_categoria'] ?? '') ?>"
                           placeholder="Ej: Semillita, Junior, Adulto, Pesado..."
                           data-msg-required="El nombre de la categoría es obligatorio"
                           data-msg-minlength="Debe tener al menos 2 caracteres"
                           data-msg-pattern="Solo letras, números, espacios, guiones y puntos"
                           data-msg-valid="Nombre válido"
                           data-hint="Entre 2 y 50 caracteres. Debe ser único.">
                    <div class="invalid-feedback d-block"><?= errorMsg('nombre_categoria', $errores) ?></div>
                </div>

                <!-- Peso mínimo -->
                <div class="col-md-6">
                    <label for="peso_min" class="form-label">Peso Mínimo (kg)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-arrow-down-circle"></i></span>
                        <input type="number"
                               name="peso_min"
                               id="peso_min"
                               class="<?= fieldClass('peso_min', $errores) ?>"
                               step="0.01"
                               min="0"
                               max="999.99"
                               value="<?= old('peso_min', $categoria['peso_min'] ?? '') ?>"
                               placeholder="Ej: 48.00"
                               data-msg-min="El peso mínimo es 0"
                               data-msg-max="El peso máximo permitido es 999.99"
                               data-msg-valid="Peso mínimo válido"
                               data-hint="Opcional. Peso mínimo de la categoría.">
                    </div>
                    <div class="invalid-feedback d-block"><?= errorMsg('peso_min', $errores) ?></div>
                </div>

                <!-- Peso máximo -->
                <div class="col-md-6">
                    <label for="peso_max" class="form-label">Peso Máximo (kg)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-arrow-up-circle"></i></span>
                        <input type="number"
                               name="peso_max"
                               id="peso_max"
                               class="<?= fieldClass('peso_max', $errores) ?>"
                               step="0.01"
                               min="0"
                               max="999.99"
                               value="<?= old('peso_max', $categoria['peso_max'] ?? '') ?>"
                               placeholder="Ej: 51.00"
                               data-msg-min="El peso máximo debe ser mayor que el peso mínimo"
                               data-msg-max="El peso máximo permitido es 999.99"
                               data-msg-valid="Peso máximo válido"
                               data-hint="Opcional. Debe ser mayor que el peso mínimo.">
                    </div>
                    <div class="invalid-feedback d-block"><?= errorMsg('peso_max', $errores) ?></div>
                </div>
            </div>

            <!-- Info adicional en edición -->
            <?php if ($isEdit): ?>
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                <strong>ID:</strong> <?= $categoria['id_categoria'] ?>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-people-fill"></i>
                                <strong>Atletas en esta categoría:</strong>
                                <?= (new Categoria())->contarAtletas((int)$categoria['id_categoria']) ?>
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
                <a href="?c=categorias&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================================
// VALIDACIÓN CRUZADA: peso_max debe ser > peso_min
// Actualizamos dinámicamente el "min" del peso_max para que
// validaciones.js lo rechace si no cumple.
// ============================================================
(function() {
    var min = document.getElementById('peso_min');
    var max = document.getElementById('peso_max');
    if (!min || !max) return;

    function actualizarMinMax() {
        var valorMin = parseFloat(min.value);
        
        if (isNaN(valorMin)) {
            // Si el min está vacío, quitar el min del peso_max
            max.removeAttribute('data-min-dinamico');
            max.setAttribute('min', '0');
        } else {
            // El peso_max debe ser al menos 0.01 mayor que el min
            var nuevoMin = (valorMin + 0.01).toFixed(2);
            max.setAttribute('min', nuevoMin);
            max.setAttribute('data-min-dinamico', nuevoMin);
        }

        // Revalidar si el campo ya fue tocado
        if (max.value !== '' && typeof window.revalidarCampo === 'function') {
            window.revalidarCampo(max);
        }
    }

    min.addEventListener('input', actualizarMinMax);
    min.addEventListener('change', actualizarMinMax);
    
    // Ejecutar al cargar (por si estamos editando)
    actualizarMinMax();
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