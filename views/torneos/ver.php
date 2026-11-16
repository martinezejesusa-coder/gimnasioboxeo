<?php
/**
 * Detalle del torneo con información general y acceso a llaves
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-trophy-fill text-warning"></i> <?= htmlspecialchars($torneo['nombre_torneo']) ?></h2>
    <div>
        <a href="?c=torneos&a=llaves&id=<?= $torneo['id_torneo'] ?>" class="btn btn-primary">
            <i class="bi bi-diagram-3"></i> Ver Llaves
        </a>
        <a href="?c=torneos&a=index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5><i class="bi bi-info-circle"></i> Información del Torneo</h5>
                <table class="table">
                    <tr>
                        <th width="30%">Nombre</th>
                        <td><?= htmlspecialchars($torneo['nombre_torneo']) ?></td>
                    </tr>
                    <tr>
                        <th>Fecha Inicio</th>
                        <td><?= date('d/m/Y', strtotime($torneo['fecha_inicio'])) ?></td>
                    </tr>
                    <?php if ($torneo['fecha_fin']): ?>
                    <tr>
                        <th>Fecha Fin</th>
                        <td><?= date('d/m/Y', strtotime($torneo['fecha_fin'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Lugar</th>
                        <td><?= htmlspecialchars($torneo['lugar'] ?? 'No especificado') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h5><i class="bi bi-diagram-3"></i> Llaves del Torneo</h5>
                <p class="text-muted">Visualiza todos los combates organizados por rondas</p>
                <a href="?c=torneos&a=llaves&id=<?= $torneo['id_torneo'] ?>" class="btn btn-primary">
                    <i class="bi bi-eye"></i> Ver Llaves
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-dark text-white">
        <h6 class="mb-0"><i class="bi bi-fire"></i> Resumen de Combates</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Ronda</th>
                        <th>Local</th>
                        <th>Visitante</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hayCombates = false;
                    foreach ($llaves as $ronda => $combates):
                        foreach ($combates as $combate):
                            $hayCombates = true;
                    ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($ronda) ?></span></td>
                        <td><?= htmlspecialchars($combate['local_nombre'] ?? 'Sin atleta') ?></td>
                        <td><?= htmlspecialchars($combate['visitante_nombre'] ?? 'Sin atleta') ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $combate['resultado'] === 'Pendiente' ? 'secondary' :
                                ($combate['resultado'] === 'Empate' ? 'info' : 'success') 
                            ?>">
                                <?= htmlspecialchars($combate['resultado']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    endforeach; 
                    if (!$hayCombates): 
                    ?>
                    <tr>
                        <td colspan="4" class="text-muted text-center">No hay combates registrados en este torneo</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>