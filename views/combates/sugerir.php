<?php
/**
 * Vista: Emparejamiento Inteligente
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0"><i class="bi bi-shuffle text-danger"></i> Emparejamiento Inteligente</h2>
    <a href="?c=combates&a=index" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left"></i> Volver a la lista
    </a>
</div>

<p class="text-muted">Sugiere parejas de atletas dentro de la misma categoría y rango de combates</p>

<form id="formSugerir" method="POST" action="?c=combates&a=sugerir" class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="selectCategoria" class="form-label fw-bold text-muted small text-uppercase">Categoría</label>
                <select name="categoria" id="selectCategoria" class="form-select select-buscable" required>
                    <option value="">-- Seleccionar --</option>
                    <?php foreach($categorias as $c): ?>
                        <option value="<?= $c['id_categoria'] ?>" <?= ($filtros['categoria'] ?? 0) == $c['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="inputMin" class="form-label fw-bold text-muted small text-uppercase">Combates Mín</label>
                <input type="number" name="min" id="inputMin" min="0" class="form-control" value="<?= htmlspecialchars($filtros['min'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label for="inputMax" class="form-label fw-bold text-muted small text-uppercase">Combates Máx</label>
                <input type="number" name="max" id="inputMax" min="0" class="form-control" value="<?= htmlspecialchars($filtros['max'] ?? 100) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" id="btnSugerir" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Sugerir
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Contenedor donde JS inyectará los resultados -->
<div id="resultadosSugerencias">
    <?php if($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
        <div class="alert alert-info text-center py-5 border-0 shadow-sm">
            <i class="bi bi-shuffle text-secondary empty-icon"></i>
            <h5 class="mt-3 fw-bold">Selecciona una categoría</h5>
            <p class="text-muted mb-0">El sistema sugerirá parejas basándose en tu selección.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    window.BASE_URL = '<?= BASE_URL ?>';
    window.ROL_USUARIO = '<?= $_SESSION['usuario']['rol'] ?? 'Consulta' ?>';
</script>