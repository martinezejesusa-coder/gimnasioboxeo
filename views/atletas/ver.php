<?php
function calcularEdad($fechaNacimiento) {
    if (empty($fechaNacimiento)) return null;
    $fecha = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    return $hoy->diff($fecha)->y;
}
$edad = calcularEdad($atleta['fecha_nacimiento'] ?? null);

function formatearFecha($fecha) {
    if (empty($fecha)) return '-';
    $date = new DateTime($fecha);
    return $date->format('d/m/Y');
}

// Definir color del badge según el nivel
$nivel = $atleta['nivel_calculado'] ?? 'Novato';
$colorBadge = 'bg-secondary';
if ($nivel === 'Intermedio') $colorBadge = 'bg-primary';
if ($nivel === 'Avanzado') $colorBadge = 'bg-warning text-dark';
if ($nivel === 'Experto') $colorBadge = 'bg-danger';
?>

<div class="d-flex justify-content-between mb-4">
    <a href="?c=atletas&a=index" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="row g-4">
    <!-- COLUMNA IZQUIERDA: FOTO Y PERFIL -->
    <div class="col-lg-4 col-md-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
            <!-- Foto con tamaño controlado -->
            <div style="height: 350px; background-color: #f1f5f9; position: relative;">
                <?php if($atleta['foto']): ?>
                    <img src="<?= BASE_URL . htmlspecialchars($atleta['foto']) ?>" 
                         style="width: 100%; height: 100%; object-fit: cover; object-position: top;"
                         alt="Foto">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <i class="bi bi-person-circle text-secondary" style="font-size: 8rem;"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                <h3 class="fw-bold mb-1"><?= htmlspecialchars($atleta['pnombre'] . ' ' . $atleta['papellido']) ?></h3>
                <p class="text-muted mb-3"><i class="bi bi-person-vcard"></i> <?= htmlspecialchars($atleta['cedula']) ?></p>
                <div>
                    <span class="badge <?= $colorBadge ?> fs-6 px-4 py-2 rounded-pill shadow-sm">
                        <?= htmlspecialchars($nivel) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: DATOS Y RÉCORD -->
    <div class="col-lg-8 col-md-7">
        
        <!-- Datos Personales -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-person-lines-fill text-danger me-2"></i> Información Personal</h5>
                <div class="row g-4">
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Fecha de Nacimiento</small>
                        <span class="fw-semibold fs-6"><?= formatearFecha($atleta['fecha_nacimiento']) ?> (<?= $edad ?> años)</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Club Actual</small>
                        <span class="fw-semibold fs-6 text-dark"><i class="bi bi-building text-muted me-2"></i><?= htmlspecialchars($atleta['nombre_club'] ?? 'Sin club') ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Categoría</small>
                        <span class="fw-semibold fs-6 text-dark"><i class="bi bi-tags text-muted me-2"></i><?= htmlspecialchars($atleta['nombre_categoria'] ?? 'Sin categoría') ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Estado</small>
                        <span class="fw-semibold fs-6">
                            <?php if(!empty($atleta['activo'])): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Récord Boxístico -->
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-trophy-fill text-warning me-2"></i> Récord Boxístico</h5>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-4 shadow-sm text-white" style="background: linear-gradient(135deg, #16a34a, #15803d);">
                            <div class="fs-2 fw-bold"><?= (int)$atleta['victorias'] ?></div>
                            <div class="text-uppercase small fw-semibold" style="letter-spacing: 1px;">Victorias</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-4 shadow-sm text-white" style="background: linear-gradient(135deg, #dc2626, #991b1b);">
                            <div class="fs-2 fw-bold"><?= (int)$atleta['derrotas'] ?></div>
                            <div class="text-uppercase small fw-semibold" style="letter-spacing: 1px;">Derrotas</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-4 shadow-sm text-white" style="background: linear-gradient(135deg, #6b7280, #374151);">
                            <div class="fs-2 fw-bold"><?= (int)$atleta['empates'] ?></div>
                            <div class="text-uppercase small fw-semibold" style="letter-spacing: 1px;">Empates</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-4 shadow-sm text-white" style="background: linear-gradient(135deg, #1a1a1a, #000);">
                            <div class="fs-2 fw-bold"><?= (int)$atleta['total_combates'] ?></div>
                            <div class="text-uppercase small fw-semibold" style="letter-spacing: 1px;">Total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- SECCIÓN INFERIOR: DOCUMENTOS Y HISTORIAL -->
<div class="row g-4 mt-2">
    <!-- Documentos -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-file-earmark-text text-primary me-2"></i> Documentos Adjuntos</h5>
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <tbody>
                        <?php foreach($documentos as $d): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded me-3 text-primary"><i class="bi bi-file-earmark fs-5"></i></div>
                                        <div>
                                            <div class="fw-semibold text-truncate" style="max-width: 180px;" title="<?= htmlspecialchars($d['nombre_documento']) ?>">
                                                <?= htmlspecialchars($d['nombre_documento']) ?>
                                            </div>
                                            <small class="text-muted"><?= formatearFecha($d['fecha_subida']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="<?= BASE_URL.$d['ruta_archivo'] ?>" target="_blank" class="btn btn-sm btn-light rounded-circle shadow-sm" title="Descargar">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; if(!$documentos): ?>
                            <tr><td class="text-muted text-center py-5"><i class="bi bi-folder-x fs-2 d-block mb-2 text-secondary"></i> Sin documentos</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-clock-history text-success me-2"></i> Historial de Combates</h5>
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0">
                        <tbody>
                        <?php foreach($historial as $c):
                          $esLocal = $c['id_atleta_local']==$atleta['id_atleta'];
                          $rival = $esLocal ? $c['visitante_nombre'] : $c['local_nombre'];
                          $res = $c['resultado'];
                          $mio = 'Pendiente';
                          if($res==='Empate') $mio='Empate';
                          elseif(($esLocal && $res==='Local') || (!$esLocal && $res==='Visitante')) $mio='Victoria';
                          elseif($res!=='Pendiente') $mio='Derrota';
                          $color = ['Victoria'=>'success','Derrota'=>'danger','Empate'=>'secondary','Pendiente'=>'warning'][$mio];
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($rival) ?></div>
                                <small class="text-muted"><?= formatearFecha($c['fecha']) ?></small>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-<?=$color?> rounded-pill px-3"><?=$mio?></span>
                            </td>
                        </tr>
                        <?php endforeach; if(!$historial): ?>
                            <tr><td class="text-muted text-center py-5"><i class="bi bi-calendar-x fs-2 d-block mb-2 text-secondary"></i> Sin combates</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>