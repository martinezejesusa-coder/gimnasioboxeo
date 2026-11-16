<?php
/**
 * Vista: Ficha detallada de un combate (Fight Card)
 * 
 */
$fotoLocal = $local['foto'] ?? null;
$clubLocal = $local['nombre_club'] ?? 'Sin club';
$fotoVisitante = $visitante['foto'] ?? null;
$clubVisitante = $visitante['nombre_club'] ?? 'Sin club';
$resultado = $combate['resultado'];
?>

<div class="d-flex justify-content-between mb-4">
    <a href="?c=combates&a=index" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left"></i> Volver a la lista
    </a>
</div>

<!-- 1. CARTELERA DEL COMBATE -->
<div class="card border-0 shadow-lg mb-4 fight-card">
    <div class="card-body p-0">
        <div class="row g-0 align-items-center text-center bg-white">
            
            <!-- Atleta Local -->
            <div class="col-md-5 p-4 <?= $resultado === 'Local' ? 'bg-success bg-opacity-10' : '' ?>">
                <div class="position-relative d-inline-block mb-3">
                    <?php if($fotoLocal): ?>
                        <img src="<?= BASE_URL . htmlspecialchars($fotoLocal) ?>" 
                             class="rounded-circle shadow-sm fighter-photo fighter-photo-local">
                    <?php else: ?>
                        <i class="bi bi-person-circle text-danger fighter-icon"></i>
                    <?php endif; ?>
                    <?php if($resultado === 'Local'): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success border border-white border-3 p-2 shadow">
                            <i class="bi bi-trophy-fill fs-5"></i>
                        </span>
                    <?php endif; ?>
                </div>
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($nombreLocal) ?></h4>
                <p class="text-muted small mb-2"><i class="bi bi-building"></i> <?= htmlspecialchars($clubLocal) ?></p>
                <span class="badge bg-danger rounded-pill px-3 py-2">🥊 LOCAL</span>
            </div>

            <!-- Centro: Resultado -->
            <div class="col-md-2 p-4 text-center d-flex flex-column justify-content-center align-items-center bg-white">
                <div class="bg-<?= $colorRes ?> text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg mb-3 result-circle">
                    <i class="bi bi-trophy-fill fs-1"></i>
                </div>
                <h5 class="fw-bold text-<?= $colorRes ?> mb-1"><?= htmlspecialchars($textoRes) ?></h5>
                <div class="text-muted small fw-bold"><?= (int)$combate['rounds_pautados'] ?> ROUNDS</div>
            </div>

            <!-- Atleta Visitante -->
            <div class="col-md-5 p-4 <?= $resultado === 'Visitante' ? 'bg-success bg-opacity-10' : '' ?>">
                <div class="position-relative d-inline-block mb-3">
                    <?php if($fotoVisitante): ?>
                        <img src="<?= BASE_URL . htmlspecialchars($fotoVisitante) ?>" 
                             class="rounded-circle shadow-sm fighter-photo fighter-photo-visitante">
                    <?php else: ?>
                        <i class="bi bi-person-circle text-primary fighter-icon"></i>
                    <?php endif; ?>
                    <?php if($resultado === 'Visitante'): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success border border-white border-3 p-2 shadow">
                            <i class="bi bi-trophy-fill fs-5"></i>
                        </span>
                    <?php endif; ?>
                </div>
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($nombreVisitante) ?></h4>
                <p class="text-muted small mb-2"><i class="bi bi-building"></i> <?= htmlspecialchars($clubVisitante) ?></p>
                <span class="badge bg-primary rounded-pill px-3 py-2">✈️ VISITANTE</span>
            </div>

        </div>
        
        <!-- Banner del Ganador -->
        <?php if ($ganadorNombre): ?>
        <div class="m-3 p-3 text-center rounded-4 winner-banner">
            <h5 class="mb-0 fw-bold text-uppercase winner-text">
                <i class="bi bi-trophy-fill me-2 winner-icon"></i> Ganador: <?= htmlspecialchars($ganadorNombre) ?>
            </h5>
        </div>
        <?php elseif ($resultado === 'Empate'): ?>
        <div class="m-3 p-3 text-center rounded-4 draw-banner">
            <h5 class="mb-0 fw-bold text-uppercase draw-text">
                <i class="bi bi-hand-thumbs-up-fill me-2 draw-icon"></i> Empate
            </h5>
        </div>
        <?php elseif ($resultado === 'Pendiente'): ?>
        <div class="m-3 p-3 text-center rounded-4 pending-banner">
            <h5 class="mb-0 fw-bold text-uppercase pending-text">
                <i class="bi bi-clock-history me-2 pending-icon"></i> Combate Pendiente
            </h5>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <!-- 2. DETALLES DEL COMBATE -->
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-info-circle text-primary me-2"></i> Información del Combate</h5>
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase mb-1 info-label">Fecha</small>
                        <span class="fw-semibold fs-6"><?= htmlspecialchars($combate['fecha']) ?></span>
                    </div>
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase mb-1 info-label">Torneo</small>
                        <span class="fw-semibold fs-6 text-truncate d-block"><?= htmlspecialchars($combate['nombre_torneo'] ?? '-') ?></span>
                    </div>
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase mb-1 info-label">Ronda en Llave</small>
                        <span class="fw-semibold fs-6"><?= (int)$combate['ronda'] ?></span>
                    </div>
                    <div class="col-md-3 col-6">
                        <small class="text-muted d-block text-uppercase mb-1 info-label">Decisión</small>
                        <span class="fw-semibold fs-6"><?= htmlspecialchars($combate['decision_victoria'] ?? 'Pendiente') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TARJETAS DE PUNTUACIÓN -->
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-clipboard-data text-success me-2"></i> Tarjetas de Puntuación</h5>
                
                <?php if(!empty($tarjetas)): ?>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded-3 text-center">
                                <h6 class="text-muted mb-1 small text-uppercase">Rounds Registrados</h6>
                                <h3 class="mb-0 fw-bold"><?= count($tarjetas) ?></h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded-3 text-center">
                                <h6 class="text-muted mb-1 small text-uppercase">Jueces</h6>
                                <h3 class="mb-0 fw-bold"><?= count($resumenJueces) ?></h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded-3 text-center">
                                <h6 class="text-muted mb-1 small text-uppercase">Rounds Pautados</h6>
                                <h3 class="mb-0 fw-bold"><?= $combate['rounds_pautados'] ?? '-' ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Juez</th>
                                    <th class="text-center">Round</th>
                                    <th class="text-center text-danger">Pts Local 🥊</th>
                                    <th class="text-center text-primary">Pts Visitante ✈️</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalesLocal = 0; $totalesVisitante = 0;
                                foreach($tarjetas as $t): 
                                    $totalesLocal += $t['puntos_local'];
                                    $totalesVisitante += $t['puntos_visitante'];
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($t['nombre_juez'] ?? 'N/A') ?></td>
                                    <td class="text-center"><span class="badge bg-dark rounded-pill"><?= (int)$t['numero_round'] ?></span></td>
                                    <td class="text-center fw-bold text-danger"><?= (int)$t['puntos_local'] ?></td>
                                    <td class="text-center fw-bold text-primary"><?= (int)$t['puntos_visitante'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light border-top-2">
                                <tr class="fw-bold">
                                    <td colspan="2" class="text-end text-uppercase">Totales:</td>
                                    <td class="text-center text-danger fs-5"><?= $totalesLocal ?></td>
                                    <td class="text-center text-primary fs-5"><?= $totalesVisitante ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if(!empty($resumenJueces)): ?>
                    <h6 class="mt-4 mb-3 fw-bold">Resumen por Juez</h6>
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Juez</th>
                                    <th class="text-center">Rounds</th>
                                    <th class="text-center">Total Local</th>
                                    <th class="text-center">Total Visitante</th>
                                    <th class="text-center">Ganador</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($resumenJueces as $r): 
                                    $ganador = 'Empate';
                                    if($r['total_local'] > $r['total_visitante']) $ganador = 'Local';
                                    elseif($r['total_visitante'] > $r['total_local']) $ganador = 'Visitante';
                                    $colorGanador = $ganador === 'Local' ? 'danger' : ($ganador === 'Visitante' ? 'primary' : 'secondary');
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($r['nombre_juez']) ?></td>
                                    <td class="text-center"><?= (int)$r['rounds'] ?></td>
                                    <td class="text-center fw-bold text-danger"><?= (int)$r['total_local'] ?></td>
                                    <td class="text-center fw-bold text-primary"><?= (int)$r['total_visitante'] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $colorGanador ?> rounded-pill px-3">
                                            <?= $ganador ?> <?= $ganador !== 'Empate' ? '<i class="bi bi-trophy-fill ms-1"></i>' : '' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center py-5 bg-light rounded-4">
                        <i class="bi bi-clipboard-x text-secondary empty-icon"></i>
                        <h5 class="mt-3 fw-bold">Sin tarjetas registradas</h5>
                        <p class="text-muted mb-0">Aún no se han ingresado puntuaciones para este combate.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>