<?php $rol = $_SESSION['usuario']['rol'] ?? 'Consulta'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2><i class="bi bi-trophy"></i> Torneos</h2>
    <?php if($rol !== 'Consulta'): ?>
        <a href="?c=torneos&a=form" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Torneo
        </a>
    <?php endif; ?>
</div>

<div class="row g-3">
    <?php foreach($torneos as $t): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-trophy-fill text-warning"></i>
                        <?= htmlspecialchars($t['nombre_torneo']) ?>
                    </h5>
                    <p class="card-text">
                        <i class="bi bi-calendar"></i> 
                        <?= date('d/m/Y', strtotime($t['fecha_inicio'])) ?>
                        <?php if ($t['fecha_fin']): ?>
                            - <?= date('d/m/Y', strtotime($t['fecha_fin'])) ?>
                        <?php endif; ?>
                        <br>
                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($t['lugar'] ?? 'Sin lugar') ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100">
                        <a href="?c=torneos&a=ver&id=<?= $t['id_torneo'] ?>" class="btn btn-sm btn-dark">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                        <a href="?c=torneos&a=llaves&id=<?= $t['id_torneo'] ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-diagram-3"></i> Llaves
                        </a>
                        <?php if($rol !== 'Consulta'): ?>
                            <a href="?c=torneos&a=form&id=<?= $t['id_torneo'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        <?php endif; ?>
                        <?php if($rol === 'Administrador'): ?>
                            <a href="?c=torneos&a=eliminar&id=<?= $t['id_torneo'] ?>" 
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('¿Eliminar el torneo &quot;<?= htmlspecialchars($t['nombre_torneo']) ?>&quot;? Solo es posible si no tiene combates asociados.')">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if(empty($torneos)): ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No hay torneos registrados.
        <?php if($rol !== 'Consulta'): ?>
            <a href="?c=torneos&a=form">Crea el primero</a>
        <?php endif; ?>
    </div>
<?php endif; ?>