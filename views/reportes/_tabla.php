<?php
$datos = $datos ?? [];
$tituloReporte = $tituloReporte ?? '';
?>

<?php if (!empty($datos)): 
    // Calcular estadísticas generales
    $totalV = 0; $totalD = 0; $totalE = 0; $totalComb = 0;
    foreach ($datos as $a) {
        $totalV += (int)($a['victorias'] ?? 0);
        $totalD += (int)($a['derrotas'] ?? 0);
        $totalE += (int)($a['empates'] ?? 0);
        $totalComb += (int)($a['total_combates'] ?? 0);
    }
    $winRateGlobal = $totalComb > 0 ? round(($totalV / $totalComb) * 100, 1) : 0;
?>

<!-- Tarjetas de resumen -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= count($datos) ?></div>
                <div class="stat-label">Atletas</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-success">
            <div class="stat-icon"><i class="bi bi-trophy-fill"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $totalV ?></div>
                <div class="stat-label">Victorias</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-danger">
            <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $totalD ?></div>
                <div class="stat-label">Derrotas</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-info">
            <div class="stat-icon"><i class="bi bi-fire"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $winRateGlobal ?>%</div>
                <div class="stat-label">Win Rate Global</div>
            </div>
        </div>
    </div>
</div>

<!-- Encabezado del reporte -->
<div class="report-header mb-3">
    <div class="report-header-left">
        <h4 class="report-title">
            <i class="bi bi-file-earmark-bar-graph text-danger"></i>
            <span id="tituloReporte"><?= htmlspecialchars($tituloReporte ?: 'Resultados') ?></span>
        </h4>
        <span class="report-badge" id="totalAtletas"><?= count($datos) ?> registros</span>
    </div>
    <div class="report-header-right">
        <a href="?<?= http_build_query(array_merge($_GET, ['c' => 'reportes', 'a' => 'exportar'])) ?>" 
           target="_blank" 
           class="btn btn-danger btn-export" id="btnExportar">
            <i class="bi bi-file-pdf"></i>
            <span>Exportar PDF</span>
        </a>
    </div>
</div>

<!-- Tabla -->
<div class="card report-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table report-table mb-0" id="tablaReporte">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Cédula</th>
                        <th>Atleta</th>
                        <th>Club</th>
                        <th class="text-center">Nivel</th>
                        <th class="text-center">V</th>
                        <th class="text-center">D</th>
                        <th class="text-center">E</th>
                        <th class="text-center">Total</th>
                        <th class="text-center" style="width: 120px;">Win Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pos = 1;
                    foreach($datos as $a): 
                        $total = (int)($a['total_combates'] ?? 0);
                        $victorias = (int)($a['victorias'] ?? 0);
                        $derrotas = (int)($a['derrotas'] ?? 0);
                        $empates = (int)($a['empates'] ?? 0);
                        $winRate = $total > 0 ? round(($victorias / $total) * 100, 1) : 0;
                        $nivel = $a['nivel_calculado'] ?? 'Novato';
                        
                        $nivelClass = $nivel === 'Novato' ? 'secondary' :
                                      ($nivel === 'Intermedio' ? 'primary' :
                                      ($nivel === 'Avanzado' ? 'warning' : 'danger'));
                        
                        $winRateClass = $winRate >= 70 ? 'success' :
                                        ($winRate >= 40 ? 'warning' : 'danger');
                    ?>
                    <tr class="report-row">
                        <td class="text-center">
                            <span class="row-number"><?= $pos++ ?></span>
                        </td>
                        <td><span class="cedula-text"><?= htmlspecialchars($a['cedula']) ?></span></td>
                        <td>
                            <div class="atleta-cell">
                                <div class="atleta-avatar">
                                    <?= strtoupper(substr($a['pnombre'], 0, 1) . substr($a['papellido'], 0, 1)) ?>
                                </div>
                                <div class="atleta-info">
                                    <strong><?= htmlspecialchars($a['pnombre'] . ' ' . $a['papellido']) ?></strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="club-text">
                                <i class="bi bi-building text-muted"></i>
                                <?= htmlspecialchars($a['nombre_club'] ?? 'Sin club') ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-level bg-<?= $nivelClass ?>">
                                <?= htmlspecialchars($nivel) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="stat-badge stat-win"><?= $victorias ?></span>
                        </td>
                        <td class="text-center">
                            <span class="stat-badge stat-loss"><?= $derrotas ?></span>
                        </td>
                        <td class="text-center">
                            <span class="stat-badge stat-draw"><?= $empates ?></span>
                        </td>
                        <td class="text-center">
                            <span class="total-badge"><?= $total ?></span>
                        </td>
                        <td class="text-center">
                            <div class="win-rate-container">
                                <div class="win-rate-bar">
                                    <div class="win-rate-fill bg-<?= $winRateClass ?>" style="width: <?= $winRate ?>%;"></div>
                                </div>
                                <span class="win-rate-text text-<?= $winRateClass ?>"><?= $winRate ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php else: ?>
<div class="empty-state">
    <div class="empty-state-icon">
        <i class="bi bi-inbox"></i>
    </div>
    <h4>No hay resultados</h4>
    <p>No se encontraron atletas que coincidan con los filtros seleccionados.</p>
</div>
<?php endif; ?>