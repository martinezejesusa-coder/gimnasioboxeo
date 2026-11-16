<?php
$tipo = $tipo ?? '';
$datos = $datos ?? [];
$clubes = $clubes ?? [];
$categorias = $categorias ?? [];
$tituloReporte = $tituloReporte ?? '';
?>

<div class="reportes-page">
    
    <!-- Header -->
    <div class="page-header mb-4">
        <div>
            <h2 class="page-title">
                <i class="bi bi-file-earmark-bar-graph"></i> 
                Reportes y Estadísticas
            </h2>
            <p class="page-subtitle">Analiza el rendimiento de los atletas del gimnasio</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters-card mb-4">
        <div class="filters-header">
            <h6 class="filters-title">
                <i class="bi bi-funnel"></i> Filtros del Reporte
            </h6>
        </div>
        <form method="GET" id="reporteForm" onsubmit="return false;">
            <input type="hidden" name="c" value="reportes">
            <input type="hidden" name="a" value="index">
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="filter-label">Tipo de Reporte</label>
                    <div class="report-type-selector">
                        <input type="radio" class="report-type-input" name="tipo" id="tipo_cat" value="atletas_categoria" 
                               <?= $tipo === 'atletas_categoria' ? 'checked' : '' ?>>
                        <label class="report-type-label" for="tipo_cat">
                            <i class="bi bi-tags-fill"></i>
                            <span>Por Categoría</span>
                        </label>
                        
                        <input type="radio" class="report-type-input" name="tipo" id="tipo_club" value="atletas_club" 
                               <?= $tipo === 'atletas_club' ? 'checked' : '' ?>>
                        <label class="report-type-label" for="tipo_club">
                            <i class="bi bi-building-fill"></i>
                            <span>Por Club</span>
                        </label>
                        
                        <input type="radio" class="report-type-input" name="tipo" id="tipo_rank" value="ranking" 
                               <?= $tipo === 'ranking' ? 'checked' : '' ?>>
                        <label class="report-type-label" for="tipo_rank">
                            <i class="bi bi-trophy-fill"></i>
                            <span>Ranking</span>
                        </label>
                        
                        <input type="radio" class="report-type-input" name="tipo" id="tipo_rango" value="rango_combates" 
                               <?= $tipo === 'rango_combates' ? 'checked' : '' ?>>
                        <label class="report-type-label" for="tipo_rango">
                            <i class="bi bi-bar-chart-fill"></i>
                            <span>Rango Combates</span>
                        </label>
                    </div>
                </div>
                
                <!-- Filtros dinámicos -->
                <?php if ($tipo === 'atletas_categoria'): ?>
                <div class="col-md-6">
                    <label for="id_categoria" class="filter-label">
                        <i class="bi bi-tags"></i> Selecciona una Categoría
                    </label>
                    <select name="id_categoria" id="id_categoria" class="form-select select-buscable">
                        <option value="">-- Seleccionar categoría --</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>" 
                                <?= ($_GET['id_categoria'] ?? '') == $c['id_categoria'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <?php if ($tipo === 'atletas_club'): ?>
                <div class="col-md-6">
                    <label for="id_club" class="filter-label">
                        <i class="bi bi-building"></i> Selecciona un Club
                    </label>
                    <select name="id_club" id="id_club" class="form-select select-buscable">
                        <option value="">-- Seleccionar club --</option>
                        <?php foreach($clubes as $c): ?>
                            <option value="<?= $c['id_club'] ?>" 
                                <?= ($_GET['id_club'] ?? '') == $c['id_club'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre_club']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <?php if ($tipo === 'rango_combates'): ?>
                <div class="col-md-3">
                    <label for="min" class="filter-label">
                        <i class="bi bi-arrow-down-circle"></i> Combates Mín
                    </label>
                    <input type="number" name="min" id="min" 
                           value="<?= $_GET['min'] ?? 0 ?>" 
                           class="form-control" min="0" max="999">
                </div>
                <div class="col-md-3">
                    <label for="max" class="filter-label">
                        <i class="bi bi-arrow-up-circle"></i> Combates Máx
                    </label>
                    <input type="number" name="max" id="max" 
                           value="<?= $_GET['max'] ?? 100 ?>" 
                           class="form-control" min="0" max="999">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="window.cargarReporte && window.cargarReporte()">
                        <i class="bi bi-search"></i> Aplicar
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    <div id="resultadosContainer">
        <?php if ($tipo): ?>
            <?php include __DIR__ . '/_tabla.php'; ?>
        <?php else: ?>
        <div class="welcome-state">
            <div class="welcome-icon">
                <i class="bi bi-file-earmark-bar-graph"></i>
            </div>
            <h4>Selecciona un tipo de reporte</h4>
            <p>Elige una de las opciones de arriba para comenzar a analizar el rendimiento de los atletas</p>
            <div class="welcome-hints">
                <div class="hint-item">
                    <i class="bi bi-tags text-primary"></i>
                    <span>Atletas por categoría</span>
                </div>
                <div class="hint-item">
                    <i class="bi bi-building text-success"></i>
                    <span>Atletas por club</span>
                </div>
                <div class="hint-item">
                    <i class="bi bi-trophy text-warning"></i>
                    <span>Ranking general</span>
                </div>
                <div class="hint-item">
                    <i class="bi bi-bar-chart text-info"></i>
                    <span>Por rango de combates</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Overlay de carga -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner">
            <div class="spinner-ring"></div>
        </div>
        <p class="loading-text">Cargando reporte...</p>
    </div>
</div>

<style>
/* ============================================================
   ESTILOS DE REPORTES
   ============================================================ */

.reportes-page {
    padding-bottom: 4rem;
}

/* Header */
.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.25rem;
}
.page-title i { color: #c8102e; }
.page-subtitle { color: #6c757d; margin: 0; font-size: 0.95rem; }

/* Filtros */
.filters-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    overflow: hidden;
}
.filters-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}
.filters-title {
    margin: 0;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.filters-card form { padding: 1.5rem; }
.filter-label {
    display: block;
    font-weight: 600;
    color: #495057;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.filter-label i { color: #c8102e; margin-right: 0.25rem; }

/* Selector de tipo de reporte (botones pastilla) */
.report-type-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0.75rem;
}
.report-type-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.report-type-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    background: #fff;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: 500;
    color: #495057;
    text-align: center;
    min-height: 90px;
}
.report-type-label i {
    font-size: 1.5rem;
    color: #6c757d;
    transition: all 0.2s ease;
}
.report-type-label span { font-size: 0.85rem; }
.report-type-label:hover {
    border-color: #c8102e;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(200, 16, 46, 0.15);
}
.report-type-label:hover i { color: #c8102e; }
.report-type-input:checked + .report-type-label {
    background: linear-gradient(135deg, #c8102e 0%, #a00d24 100%);
    border-color: #c8102e;
    color: #fff;
    box-shadow: 0 4px 12px rgba(200, 16, 46, 0.3);
    transform: translateY(-2px);
}
.report-type-input:checked + .report-type-label i { color: #fff; }

/* ⭐ ESTILOS DE LAS TARJETAS DE RESUMEN (STAT CARDS) */
.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    border-left: 4px solid;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
.stat-card-primary { border-left-color: #0d6efd; }
.stat-card-success { border-left-color: #198754; }
.stat-card-danger { border-left-color: #dc3545; }
.stat-card-info { border-left-color: #0dcaf0; }

.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.stat-card-primary .stat-icon { background: #cfe2ff; color: #0d6efd; }
.stat-card-success .stat-icon { background: #d1e7dd; color: #198754; }
.stat-card-danger .stat-icon { background: #f8d7da; color: #dc3545; }
.stat-card-info .stat-icon { background: #cff4fc; color: #0dcaf0; }

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.2;
}
.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

/* ⭐ HEADER DEL REPORTE */
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}
.report-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.report-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1a1a1a;
}
.report-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #e9ecef;
    color: #495057;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    transition: all 0.2s ease;
}
.btn-export:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

/* ⭐ TABLA */
.report-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    overflow: hidden;
}
.report-table {
    margin: 0;
}
.report-table thead th {
    background: #1a1a1a;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem;
    border: none;
}
.report-table tbody td {
    padding: 0.85rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f5;
    font-size: 0.9rem;
}
.report-row {
    transition: background 0.15s ease;
}
.report-row:hover {
    background: #f8f9fa;
}
.row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: #e9ecef;
    color: #495057;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}
.cedula-text {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: #495057;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Avatar del atleta */
.atleta-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.atleta-avatar {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #c8102e 0%, #a00d24 100%);
    color: #fff;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.85rem;
    flex-shrink: 0;
}
.atleta-info {
    display: flex;
    flex-direction: column;
}
.atleta-info strong {
    font-size: 0.9rem;
    color: #1a1a1a;
}

/* Club */
.club-text {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.85rem;
    color: #495057;
}

/* Badges de nivel */
.badge-level {
    padding: 0.35rem 0.7rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
}

/* Badges de stats */
.stat-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 28px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 700;
}
.stat-win { background: #d1e7dd; color: #0a3622; }
.stat-loss { background: #f8d7da; color: #58151c; }
.stat-draw { background: #e2e3e5; color: #2b2f32; }

.total-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 28px;
    background: #1a1a1a;
    color: #fff;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
}

/* Win rate */
.win-rate-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.35rem;
}
.win-rate-bar {
    width: 80px;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}
.win-rate-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
}
.win-rate-text {
    font-size: 0.75rem;
    font-weight: 700;
}

/* Estado vacío */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.empty-state-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}
.empty-state h4 {
    color: #495057;
    font-weight: 600;
}
.empty-state p {
    color: #6c757d;
}

/* Estado de bienvenida */
.welcome-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.welcome-icon {
    font-size: 5rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}
.welcome-state h4 {
    color: #1a1a1a;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.welcome-state p {
    color: #6c757d;
    margin-bottom: 2rem;
}
.welcome-hints {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    max-width: 800px;
    margin: 0 auto;
}
.hint-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    text-align: left;
}
.hint-item i {
    font-size: 1.5rem;
}
.hint-item span {
    font-size: 0.9rem;
    font-weight: 500;
    color: #495057;
}

/* Overlay de carga */
.loading-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}
.loading-overlay.active {
    display: flex;
}
.loading-spinner {
    display: flex;
    align-items: center;
    justify-content: center;
}
.spinner-ring {
    width: 60px;
    height: 60px;
    border: 4px solid #e9ecef;
    border-top-color: #c8102e;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.loading-text {
    margin-top: 1.5rem;
    color: #495057;
    font-weight: 600;
    font-size: 1rem;
}

#resultadosContainer {
    transition: opacity 0.3s ease;
}
#resultadosContainer.loading {
    opacity: 0.3;
}

/* Responsive */
@media (max-width: 768px) {
    .report-type-selector {
        grid-template-columns: 1fr;
    }
    .stat-card {
        padding: 1rem;
    }
    .stat-value {
        font-size: 1.25rem;
    }
    .report-header {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Select2 personalizado */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 42px;
    border-radius: 8px;
    border-color: #dee2e6;
}
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    padding-left: 14px;
}
</style>

<script>
// ============================================================
// AJAX DINÁMICO - REPORTES
// ============================================================
(function() {
    'use strict';
    
    var form = document.getElementById('reporteForm');
    var resultadosContainer = document.getElementById('resultadosContainer');
    var loadingOverlay = document.getElementById('loadingOverlay');
    var ultimaPeticion = null;
    var timeoutDebounce = null;
    var dataTableInstance = null;
    var ordenActual = { columna: '', direccion: '' };
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
    
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function obtenerParametros() {
        var formData = new FormData(form);
        var params = new URLSearchParams();
        for (var [key, value] of formData.entries()) {
            if (value !== '' && value !== null) params.append(key, value);
        }
        if (ordenActual.columna) {
            params.set('orden_columna', ordenActual.columna);
            params.set('orden_direccion', ordenActual.direccion);
        }
        return params;
    }
    
    function cargarReporte() {
        var params = obtenerParametros();
        var tipo = params.get('tipo');
        
        if (!tipo) {
            resultadosContainer.innerHTML = 
                '<div class="welcome-state">' +
                    '<div class="welcome-icon"><i class="bi bi-file-earmark-bar-graph"></i></div>' +
                    '<h4>Selecciona un tipo de reporte</h4>' +
                    '<p>Elige una de las opciones de arriba para comenzar</p>' +
                '</div>';
            return;
        }
        
        if (tipo === 'atletas_categoria' && !params.get('id_categoria')) return;
        if (tipo === 'atletas_club' && !params.get('id_club')) return;
        
        params.set('c', 'reportes');
        params.set('a', 'ajax');
        
        if (ultimaPeticion) ultimaPeticion.abort();
        loadingOverlay.classList.add('active');
        resultadosContainer.classList.add('loading');
        
        var controller = new AbortController();
        ultimaPeticion = controller;
        
        fetch('index.php?' + params.toString(), {
            signal: controller.signal,
            headers: { 'Accept': 'application/json' }
        })
            .then(function(response) {
                if (!response.ok) throw new Error('Error HTTP ' + response.status);
                var ct = response.headers.get('content-type');
                if (!ct || !ct.includes('application/json')) {
                    return response.text().then(function(txt) {
                        throw new Error('Respuesta no es JSON: ' + txt.substring(0, 200));
                    });
                }
                return response.json();
            })
            .then(function(data) {
                if (data.status === 'error') throw new Error(data.message || 'Error');
                renderizarResultados(data);
                var nuevaUrl = window.location.pathname + '?' + params.toString();
                window.history.pushState({}, '', nuevaUrl);
            })
            .catch(function(error) {
                if (error.name === 'AbortError') return;
                console.error('❌ Error:', error);
                resultadosContainer.innerHTML =
                    '<div class="alert alert-danger">' +
                    '<i class="bi bi-exclamation-triangle"></i> Error: ' + escapeHtml(error.message) +
                    '</div>';
            })
            .finally(function() {
                ultimaPeticion = null;
                loadingOverlay.classList.remove('active');
                resultadosContainer.classList.remove('loading');
            });
    }
    
    window.cargarReporte = cargarReporte;
    
    function cargarReporteDebounce() {
        clearTimeout(timeoutDebounce);
        timeoutDebounce = setTimeout(cargarReporte, 250);
    }
    
    function renderizarResultados(data) {
        if (!data.datos || data.datos.length === 0) {
            resultadosContainer.innerHTML =
                '<div class="empty-state">' +
                    '<div class="empty-state-icon"><i class="bi bi-inbox"></i></div>' +
                    '<h4>No hay resultados</h4>' +
                    '<p>No se encontraron atletas que coincidan con los filtros.</p>' +
                '</div>';
            return;
        }
        
        var totalV = 0, totalD = 0, totalE = 0, totalComb = 0;
        data.datos.forEach(function(a) {
            totalV += parseInt(a.victorias) || 0;
            totalD += parseInt(a.derrotas) || 0;
            totalE += parseInt(a.empates) || 0;
            totalComb += parseInt(a.total_combates) || 0;
        });
        var winRateGlobal = totalComb > 0 ? Math.round((totalV / totalComb) * 1000) / 10 : 0;
        
        var html = '';
        html += '<div class="row g-3 mb-4">';
        html += tarjetaResumen('primary', 'people-fill', data.total, 'Atletas');
        html += tarjetaResumen('success', 'trophy-fill', totalV, 'Victorias');
        html += tarjetaResumen('danger', 'x-circle-fill', totalD, 'Derrotas');
        html += tarjetaResumen('info', 'fire', winRateGlobal + '%', 'Win Rate Global');
        html += '</div>';
        
        html += '<div class="report-header mb-3">';
        html += '<div class="report-header-left">';
        html += '<h4 class="report-title"><i class="bi bi-file-earmark-bar-graph text-danger"></i> ' + escapeHtml(data.titulo) + '</h4>';
        html += '<span class="report-badge">' + data.total + ' registros</span>';
        html += '</div>';
        html += '<div class="report-header-right">';
        html += '<button type="button" class="btn btn-danger btn-export" id="btnExportarAjax"><i class="bi bi-file-pdf"></i><span>Exportar PDF</span></button>';
        html += '</div>';
        html += '</div>';
        
        html += '<div class="card report-card"><div class="card-body p-0"><div class="table-responsive">';
        html += '<table class="table report-table mb-0" id="tablaReporte">';
        html += '<thead><tr>';
        html += '<th class="text-center" style="width:50px;">#</th>';
        html += '<th>Cédula</th>';
        html += '<th>Atleta</th>';
        html += '<th>Club</th>';
        html += '<th class="text-center">Nivel</th>';
        html += '<th class="text-center">V</th>';
        html += '<th class="text-center">D</th>';
        html += '<th class="text-center">E</th>';
        html += '<th class="text-center">Total</th>';
        html += '<th class="text-center" style="width:120px;">Win Rate</th>';
        html += '</tr></thead><tbody>';
        
        data.datos.forEach(function(a, idx) {
            var total = parseInt(a.total_combates) || 0;
            var victorias = parseInt(a.victorias) || 0;
            var derrotas = parseInt(a.derrotas) || 0;
            var empates = parseInt(a.empates) || 0;
            var winRate = total > 0 ? Math.round((victorias / total) * 1000) / 10 : 0;
            var nivel = a.nivel_calculado || 'Novato';
            var nivelClass = nivel === 'Novato' ? 'secondary' : (nivel === 'Intermedio' ? 'primary' : (nivel === 'Avanzado' ? 'warning' : 'danger'));
            var winRateClass = winRate >= 70 ? 'success' : (winRate >= 40 ? 'warning' : 'danger');
            var iniciales = (a.pnombre.charAt(0) + a.papellido.charAt(0)).toUpperCase();
            
            html += '<tr class="report-row">';
            html += '<td class="text-center" data-order="' + (idx + 1) + '"><span class="row-number">' + (idx + 1) + '</span></td>';
            html += '<td data-order="' + escapeHtml(a.cedula) + '"><span class="cedula-text">' + escapeHtml(a.cedula) + '</span></td>';
            html += '<td data-order="' + escapeHtml(a.pnombre + ' ' + a.papellido) + '"><div class="atleta-cell"><div class="atleta-avatar">' + iniciales + '</div><div class="atleta-info"><strong>' + escapeHtml(a.pnombre + ' ' + a.papellido) + '</strong></div></div></td>';
            html += '<td data-order="' + escapeHtml(a.nombre_club || 'Sin club') + '"><span class="club-text"><i class="bi bi-building text-muted"></i> ' + escapeHtml(a.nombre_club || 'Sin club') + '</span></td>';
            html += '<td class="text-center" data-order="' + nivel + '"><span class="badge badge-level bg-' + nivelClass + '">' + escapeHtml(nivel) + '</span></td>';
            html += '<td class="text-center" data-order="' + victorias + '"><span class="stat-badge stat-win">' + victorias + '</span></td>';
            html += '<td class="text-center" data-order="' + derrotas + '"><span class="stat-badge stat-loss">' + derrotas + '</span></td>';
            html += '<td class="text-center" data-order="' + empates + '"><span class="stat-badge stat-draw">' + empates + '</span></td>';
            html += '<td class="text-center" data-order="' + total + '"><span class="total-badge">' + total + '</span></td>';
            html += '<td class="text-center" data-order="' + winRate + '"><div class="win-rate-container"><div class="win-rate-bar"><div class="win-rate-fill bg-' + winRateClass + '" style="width:' + winRate + '%"></div></div><span class="win-rate-text text-' + winRateClass + '">' + winRate + '%</span></div></td>';
            html += '</tr>';
        });
        
        html += '</tbody></table></div></div></div>';
        resultadosContainer.innerHTML = html;
        
        inicializarDataTable();
        
        var btnExp = document.getElementById('btnExportarAjax');
        if (btnExp) {
            btnExp.addEventListener('click', function() {
                var params = obtenerParametros();
                params.set('c', 'reportes');
                params.set('a', 'exportar');
                window.open('index.php?' + params.toString(), '_blank');
            });
        }
    }
    
    var mapeoColumnas = {
        0: '', 1: 'cedula', 2: 'nombre', 3: 'club', 4: 'nivel',
        5: 'victorias', 6: 'derrotas', 7: 'empates', 8: 'total', 9: 'winrate'
    };
    
    function inicializarDataTable() {
        if (!window.jQuery || !jQuery.fn || !jQuery.fn.DataTable) return;
        
        if (dataTableInstance) {
            try { dataTableInstance.destroy(); } catch(e) {}
            dataTableInstance = null;
        }
        if (jQuery.fn.DataTable.isDataTable('#tablaReporte')) {
            jQuery('#tablaReporte').DataTable().destroy();
        }
        
        dataTableInstance = jQuery('#tablaReporte').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            order: [],
            responsive: true,
            autoWidth: false,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        });
        
        dataTableInstance.on('order.dt', function() {
            var order = dataTableInstance.order();
            if (order && order.length > 0) {
                ordenActual.columna = mapeoColumnas[order[0][0]] || '';
                ordenActual.direccion = order[0][1].toUpperCase();
            } else {
                ordenActual.columna = '';
                ordenActual.direccion = '';
            }
        });
    }
    
    function tarjetaResumen(color, icono, valor, label) {
        return '<div class="col-md-3"><div class="stat-card stat-card-' + color + '">' +
            '<div class="stat-icon"><i class="bi bi-' + icono + '"></i></div>' +
            '<div class="stat-content"><div class="stat-value">' + valor + '</div>' +
            '<div class="stat-label">' + label + '</div></div></div></div>';
    }
    
    document.querySelectorAll('input[name="tipo"]').forEach(function(radio) {
        radio.addEventListener('change', function() { 
            window.location.href = '?c=reportes&a=index&tipo=' + this.value;
        });
    });
    
    window.addEventListener('load', function() {
        if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
            ['#id_categoria', '#id_club'].forEach(function(selector) {
                var $el = jQuery(selector);
                if ($el.length && !$el.hasClass('select2-hidden-accessible')) {
                    $el.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        language: 'es',
                        placeholder: '🔍 Buscar...',
                        allowClear: true
                    });
                    $el.on('select2:select select2:clear', function() {
                        cargarReporteDebounce();
                    });
                }
            });
        }
        
        document.getElementById('id_categoria')?.addEventListener('change', function() {
            if (!window.jQuery || !jQuery.fn || !jQuery.fn.select2 || !jQuery(this).hasClass('select2-hidden-accessible')) {
                cargarReporteDebounce();
            }
        });
        document.getElementById('id_club')?.addEventListener('change', function() {
            if (!window.jQuery || !jQuery.fn || !jQuery.fn.select2 || !jQuery(this).hasClass('select2-hidden-accessible')) {
                cargarReporteDebounce();
            }
        });
        
        document.getElementById('min')?.addEventListener('change', cargarReporteDebounce);
        document.getElementById('max')?.addEventListener('change', cargarReporteDebounce);
    });
})();
</script>