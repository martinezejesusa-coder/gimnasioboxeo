<?php $rol = $_SESSION['usuario']['rol'] ?? 'Consulta'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2><i class="bi bi-building"></i> Clubes</h2>
    <?php if($rol !== 'Consulta'): ?>
        <a href="?c=clubes&a=form" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Club
        </a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <?php foreach($clubes as $c): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <?php if($c['logo']): ?>
                    <img src="<?= BASE_URL . htmlspecialchars($c['logo']) ?>" class="club-logo mb-3" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                <?php else: ?>
                    <i class="bi bi-building" style="font-size: 4rem; color: #c8102e"></i>
                <?php endif; ?>
                
                <h5 class="card-title"><?= htmlspecialchars($c['nombre_club']) ?></h5>
                
                <p class="text-muted small mb-1">
                    <i class="bi bi-geo-alt"></i> 
                    <?php 
                    // Adaptado a los campos del JOIN
                    $partes = array_filter([
                        $c['nombre_parroquia'] ?? null,
                        $c['nombre_municipio'] ?? null,
                        $c['nombre_estado'] ?? null
                    ]);
                    echo htmlspecialchars(implode(', ', $partes) ?: 'Ubicación no especificada');
                    ?>
                </p>
                
                <p class="text-muted small">
                    <i class="bi bi-person"></i> <?= htmlspecialchars($c['entrenador'] ?? 'Sin entrenador asignado') ?>
                </p>
                
                                <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                    <a href="?c=clubes&a=ver&id=<?= $c['id_club'] ?>" class="btn btn-sm btn-dark">
                        <i class="bi bi-eye"></i> Ver Ficha
                    </a>
                    
                    <?php if($rol !== 'Consulta'): ?>
                        <a href="?c=clubes&a=form&id=<?= $c['id_club'] ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if($rol === 'Administrador'): ?>
                        <a href="?c=clubes&a=eliminar&id=<?= $c['id_club'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('¿Estás seguro de que deseas eliminar este club? Solo es posible si no tiene atletas asociados.')">
                            <i class="bi bi-trash"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if(empty($clubes)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No hay clubes registrados. 
    <?php if($rol !== 'Consulta'): ?>
        <a href="?c=clubes&a=form">Crea el primero</a>
    <?php endif; ?>
</div>
<?php endif; ?>