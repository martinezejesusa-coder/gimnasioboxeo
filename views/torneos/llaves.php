<?php
/**
 * Vista de llaves del torneo
 * 
 * Muestra un bracket visual con todos los combates organizados por rondas
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-trophy"></i> <?= htmlspecialchars($torneo['nombre_torneo']) ?></h2>
    <div>
        <a href="?c=torneos&a=ver&id=<?= $torneo['id_torneo'] ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- Información del torneo -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <i class="bi bi-calendar"></i> 
                <?= date('d/m/Y', strtotime($torneo['fecha_inicio'])) ?>
                <?php if ($torneo['fecha_fin']): ?>
                    - <?= date('d/m/Y', strtotime($torneo['fecha_fin'])) ?>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($torneo['lugar'] ?? 'Lugar no especificado') ?>
            </div>
            <div class="col-md-3">
                <span class="badge bg-primary">
                    <?php 
                    $totalCombates = 0;
                    foreach ($llaves as $ronda => $combates) {
                        $totalCombates += count($combates);
                    }
                    echo $totalCombates . ' combates';
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- LLAVES VISUALES -->
<div class="llaves-container">
    <div class="row g-3">
        <?php foreach ($llaves as $ronda => $combates): ?>
            <div class="col-md">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white text-center">
                        <h6 class="mb-0">
                            <i class="bi bi-<?= 
                                $ronda == 'Final' ? 'trophy' : 
                                ($ronda == 'Semifinal' ? 'shield' : 
                                ($ronda == 'Cuartos' ? 'grid' : 'circle')) 
                            ?>"></i>
                            <?= htmlspecialchars($ronda) ?>
                            <span class="badge bg-light text-dark ms-1"><?= count($combates) ?></span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($combates as $combate): ?>
                            <div class="combate-item card mb-2 p-2 <?= $combate['resultado'] !== 'Pendiente' ? 'border-success' : 'border-secondary' ?>">
                                <div class="row g-0 align-items-center">
                                    <!-- Atleta Local -->
                                    <div class="col-5 text-center <?= $combate['resultado'] === 'Local' ? 'bg-success bg-opacity-10 rounded' : '' ?>">
                                        <?php if (!empty($combate['local_foto'])): ?>
                                            <img src="<?= BASE_URL . htmlspecialchars($combate['local_foto']) ?>" 
                                                 class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                                        <?php endif; ?>
                                        <div class="small fw-bold <?= $combate['resultado'] === 'Local' ? 'text-success' : '' ?>">
                                            <?= htmlspecialchars($combate['local_nombre'] ?? 'Sin atleta') ?>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.6rem;">
                                            <?= htmlspecialchars($combate['local_nivel'] ?? '') ?>
                                        </small>
                                        <?php if ($combate['resultado'] === 'Local'): ?>
                                            <span class="badge bg-success" style="font-size: 0.5rem;">Ganador</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- VS -->
                                    <div class="col-2 text-center">
                                        <span class="badge bg-<?= 
                                            $combate['resultado'] === 'Pendiente' ? 'secondary' : 
                                            ($combate['resultado'] === 'Empate' ? 'info' : 'success') 
                                        ?>">
                                            <?= $combate['resultado'] === 'Pendiente' ? 'VS' : $combate['resultado'] ?>
                                        </span>
                                    </div>

                                    <!-- Atleta Visitante -->
                                    <div class="col-5 text-center <?= $combate['resultado'] === 'Visitante' ? 'bg-success bg-opacity-10 rounded' : '' ?>">
                                        <?php if (!empty($combate['visitante_foto'])): ?>
                                            <img src="<?= BASE_URL . htmlspecialchars($combate['visitante_foto']) ?>" 
                                                 class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                                        <?php endif; ?>
                                        <div class="small fw-bold <?= $combate['resultado'] === 'Visitante' ? 'text-success' : '' ?>">
                                            <?= htmlspecialchars($combate['visitante_nombre'] ?? 'Sin atleta') ?>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.6rem;">
                                            <?= htmlspecialchars($combate['visitante_nivel'] ?? '') ?>
                                        </small>
                                        <?php if ($combate['resultado'] === 'Visitante'): ?>
                                            <span class="badge bg-success" style="font-size: 0.5rem;">Ganador</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- Posición en la llave -->
                                <?php if (!empty($combate['posicion_llave'])): ?>
                                    <div class="text-center mt-1">
                                        <small class="text-muted" style="font-size: 0.6rem;">
                                            <i class="bi bi-hash"></i> Posición: <?= $combate['posicion_llave'] ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($combates)): ?>
                            <div class="text-muted text-center small py-3">
                                <i class="bi bi-box"></i> Sin combates en esta ronda
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (empty($llaves)): ?>
<div class="alert alert-info text-center py-4">
    <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
    <h5>No hay combates registrados para este torneo</h5>
    <p class="text-muted">Agrega combates al torneo para comenzar a ver las llaves.</p>
    <a href="?c=combates&a=form&id_torneo=<?= $torneo['id_torneo'] ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Agregar Combate
    </a>
</div>
<?php endif; ?>

<style>
/* Contenedor principal */
.llaves-container {
    overflow-x: auto;
    padding-bottom: 10px;
}

.llaves-container .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    min-width: 200px;
}

.llaves-container .card:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

/* Combate individual */
.combate-item {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    border-radius: 8px;
}

.combate-item:hover {
    background-color: #f8f9fa;
    border-left-color: #0d6efd;
}

/* Badge de ganador */
.badge-gold {
    background: linear-gradient(135deg, #ffd700, #f0c000);
    color: #333;
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .llaves-container .card {
        min-width: 150px;
    }
    .combate-item .col-5 .small {
        font-size: 0.65rem;
    }
}
</style>