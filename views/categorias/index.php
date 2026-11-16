<?php $rol = $_SESSION['usuario']['rol'] ?? 'Consulta'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2><i class="bi bi-tags"></i> Categorías de Peso</h2>
    <?php if($rol === 'Administrador'): ?>
        <a href="?c=categorias&a=form" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Categoría
        </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Peso Mínimo</th>
                        <th>Peso Máximo</th>
                        <th>Rango</th>
                        <th>Atletas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pos = 1;
                    foreach($categorias as $c): 
                        $cantAtletas = $atletasPorCategoria[$c['id_categoria']] ?? 0;
                    ?>
                    <tr>
                        <td><?= $pos++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-tag-fill me-2 text-primary"></i>
                                <?= htmlspecialchars($c['nombre_categoria']) ?>
                            </div>
                        </td>
                        <td>
                            <?= $c['peso_min'] !== null 
                                ? number_format((float)$c['peso_min'], 2) . ' kg' 
                                : '<span class="text-muted">—</span>' ?>
                        </td>
                        <td>
                            <?= $c['peso_max'] !== null 
                                ? number_format((float)$c['peso_max'], 2) . ' kg' 
                                : '<span class="text-muted">—</span>' ?>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <?php if ($c['peso_min'] === null && $c['peso_max'] === null): ?>
                                    Sin rango
                                <?php else: ?>
                                    <?= ($c['peso_min'] !== null ? number_format((float)$c['peso_min'], 2) : '?') ?>
                                    —
                                    <?= ($c['peso_max'] !== null ? number_format((float)$c['peso_max'], 2) : '?') ?> kg
                                <?php endif; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($cantAtletas > 0): ?>
                                <span class="badge bg-primary"><?= $cantAtletas ?> atleta(s)</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <?php if($rol === 'Administrador'): ?>
                                    <a class="btn btn-sm btn-warning" href="?c=categorias&a=form&id=<?= $c['id_categoria'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar esta categoría? Solo es posible si no tiene atletas asociados.')" 
                                       href="?c=categorias&a=eliminar&id=<?= $c['id_categoria'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(empty($categorias)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No hay categorías registradas. 
    <?php if($rol === 'Administrador'): ?>
        <a href="?c=categorias&a=form">Registra la primera</a>
    <?php endif; ?>
</div>
<?php endif; ?>