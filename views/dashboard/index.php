<?php
/**
 * Dashboard principal
 * Muestra estadísticas y resumen del sistema
 */
?>

<h2 class="mb-4"><i class="bi bi-speedometer2"></i> Panel Principal</h2>

<!-- ============================================================ -->
<!-- 1. TARJETAS DE ESTADÍSTICAS - 4 COLUMNAS -->
<!-- ============================================================ -->
<div class="row g-4 mb-4">
    <!-- Atletas -->
    <div class="col-md-3">
        <div class="card card-stat bg-rojo p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value"><?= $stats['atletas'] ?></div>
                    <div class="stat-label">Atletas activos</div>
                    <small class="text-white-50"><?= $stats['atletas_inactivos'] ?> inactivos</small>
                </div>
                <i class="bi bi-person-arms-up stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Combates -->
    <div class="col-md-3">
        <div class="card card-stat bg-amarillo p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value"><?= $stats['combates'] ?></div>
                    <div class="stat-label">Combates totales</div>
                    <small class="text-white-50"><?= $stats['combates_pendientes'] ?> pendientes</small>
                </div>
                <i class="bi bi-fire stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Torneos Activos -->
    <div class="col-md-3">
        <div class="card card-stat bg-verde p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value"><?= $stats['torneos_activos'] ?? 0 ?></div>
                    <div class="stat-label">Torneos activos</div>
                    <small class="text-white-50"><?= $stats['torneos_proximos'] ?? 0 ?> próximos</small>
                </div>
                <i class="bi bi-trophy stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Clubes -->
    <div class="col-md-3">
        <div class="card card-stat bg-azul p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value"><?= $stats['clubes'] ?></div>
                    <div class="stat-label">Clubes</div>
                    <small class="text-white-50"><?= $stats['entrenadores'] ?> entrenadores</small>
                </div>
                <i class="bi bi-building stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- 2. SEGUNDA FILA - COMBATES MES + NIVELES -->
<!-- ============================================================ -->
<div class="row g-4 mb-4">
    <!-- Combates del mes -->
    <div class="col-md-3">
        <div class="card card-stat bg-dark p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value"><?= $combatesMes ?></div>
                    <div class="stat-label">Combates este mes</div>
                    <small class="text-white-50"><?= date('F Y') ?></small>
                </div>
                <i class="bi bi-calendar-event stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Atletas por nivel -->
    <div class="col-md-9">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="row w-100">
                    <div class="col-12">
                        <h6 class="mb-3"><i class="bi bi-graph-up"></i> Atletas por nivel</h6>
                    </div>
                    <div class="col-3 text-center">
                        <span class="badge bg-secondary" style="font-size: 0.9rem;">Novato</span>
                        <h3 class="mb-0 mt-1"><?= $atletasNivel['Novato'] ?></h3>
                    </div>
                    <div class="col-3 text-center">
                        <span class="badge bg-primary" style="font-size: 0.9rem;">Intermedio</span>
                        <h3 class="mb-0 mt-1"><?= $atletasNivel['Intermedio'] ?></h3>
                    </div>
                    <div class="col-3 text-center">
                        <span class="badge bg-warning" style="font-size: 0.9rem;">Avanzado</span>
                        <h3 class="mb-0 mt-1"><?= $atletasNivel['Avanzado'] ?></h3>
                    </div>
                    <div class="col-3 text-center">
                        <span class="badge bg-danger" style="font-size: 0.9rem;">Experto</span>
                        <h3 class="mb-0 mt-1"><?= $atletasNivel['Experto'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- 3. ACCIONES RÁPIDAS -->
<!-- ============================================================ -->
<div class="card mb-4">
    <div class="card-body">
        <h6 class="mb-3"><i class="bi bi-lightning text-warning"></i> Acciones Rápidas</h6>
        <div class="d-flex flex-wrap gap-2">
            <a href="?c=atletas&a=form" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus"></i> Nuevo Atleta
            </a>
            <a href="?c=combates&a=form" class="btn btn-sm btn-danger">
                <i class="bi bi-fire"></i> Registrar Combate
            </a>
            <a href="?c=torneos&a=form" class="btn btn-sm btn-warning">
                <i class="bi bi-trophy"></i> Crear Torneo
            </a>
            <a href="?c=combates&a=sugerir" class="btn btn-sm btn-info text-white">
                <i class="bi bi-shuffle"></i> Emparejamiento
            </a>
            <a href="?c=reportes&a=index" class="btn btn-sm btn-secondary">
                <i class="bi bi-file-earmark-bar-graph"></i> Ver Reportes
            </a>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- 4. TORNEOS EN CURSO -->
<!-- ============================================================ -->
<?php if (!empty($torneosDetalle)): ?>
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="bi bi-trophy"></i> Torneos en curso
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($torneosDetalle as $t): ?>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                            <div>
                                <strong><?= htmlspecialchars($t['nombre_torneo']) ?></strong>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> <?= date('d/m/Y', strtotime($t['fecha_inicio'])) ?>
                                    <?php if ($t['fecha_fin']): ?>
                                        - <?= date('d/m/Y', strtotime($t['fecha_fin'])) ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <span class="badge bg-success">Activo</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================ -->
<!-- 5. ÚLTIMOS COMBATES -->
<!-- ============================================================ -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-clock-history"></i> Últimos combates
    </div>
    <div class="card-body">
        <?php if (!empty($ultimos)): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Local</th>
                        <th>Visitante</th>
                        <th>Ronda</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($ultimos as $c): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($c['fecha'])) ?></td>
                        <td><strong><?= htmlspecialchars($c['local_nombre']) ?></strong></td>
                        <td><strong><?= htmlspecialchars($c['visitante_nombre']) ?></strong></td>
                        <td><?= $c['ronda'] ?></td>
                        <td>
                            <span class="badge bg-<?= $c['resultado'] === 'Pendiente' ? 'warning' : ($c['resultado'] === 'Empate' ? 'info' : 'success') ?>">
                                <?= $c['resultado'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="text-center text-muted py-3 mb-0">
                <i class="bi bi-inbox"></i> Sin combates registrados aún
            </p>
        <?php endif; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- 6. ESTILOS CSS -->
<!-- ============================================================ -->
<style>
/* Tarjetas de estadísticas */
.card-stat {
    color: white;
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: default;
    border: none;
    min-height: 110px;
}
.card-stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.stat-value {
    font-size: 2.2rem;
    font-weight: 700;
    line-height: 1.2;
}
.stat-label {
    font-size: 0.85rem;
    opacity: 0.85;
}
.stat-icon {
    font-size: 2.8rem;
    opacity: 0.3;
}

/* Colores de fondo */
.bg-rojo { background: linear-gradient(135deg, #dc3545, #b02a37); }
.bg-amarillo { background: linear-gradient(135deg, #ffc107, #d39e00); }
.bg-oscuro { background: linear-gradient(135deg, #212529, #343a40); }
.bg-azul { background: linear-gradient(135deg, #0d6efd, #0a58ca); }
.bg-verde { background: linear-gradient(135deg, #198754, #157347); }

/* Tabla */
.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Mejoras visuales */
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.card-header {
    border: none;
}
</style>
