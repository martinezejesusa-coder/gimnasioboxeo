<?php 
$nivelColor = function($nivel) {
    if ($nivel === 'Intermedio') return 'bg-primary';
    if ($nivel === 'Avanzado') return 'bg-warning text-dark';
    if ($nivel === 'Experto') return 'bg-danger';
    return 'bg-secondary';
};
?>

<div class="d-flex justify-content-between mb-3">
    <a href="?c=clubes&a=index" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<div class="row g-4">
    <!-- PERFIL DEL CLUB -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-body text-center p-4 d-flex flex-column justify-content-center align-items-center">
                <?php if($club['logo']): ?>
                    <img src="<?= BASE_URL . htmlspecialchars($club['logo']) ?>" class="mb-3 rounded-4 shadow-sm" style="width: 150px; height: 150px; object-fit: contain; background: #f8fafc; padding: 10px;">
                <?php else: ?>
                    <div class="bg-light rounded-4 d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 150px; height: 150px;">
                        <i class="bi bi-building text-secondary" style="font-size: 5rem;"></i>
                    </div>
                <?php endif; ?>
                
                <h3 class="fw-bold mb-2"><?= htmlspecialchars($club['nombre_club']) ?></h3>
                
                <div class="w-100 mt-3 text-start">
                    <p class="mb-2"><i class="bi bi-geo-alt-fill text-danger me-2"></i> 
                        <strong>Dirección:</strong> <?= htmlspecialchars($club['ubicacion'] ?? 'No especificada') ?>
                    </p>
                    <?php 
                    $partes = array_filter([
                        $club['nombre_parroquia'] ?? null,
                        $club['nombre_municipio'] ?? null,
                        $club['nombre_estado'] ?? null
                    ]);
                    if (!empty($partes)): ?>
                        <p class="text-muted small mb-3 ps-4">
                            <?= htmlspecialchars(implode(', ', $partes)) ?>
                        </p>
                    <?php else: ?>
                        <p class="text-muted small mb-3 ps-4">Ubicación no registrada</p>
                    <?php endif; ?>
                    
                    <p class="mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i> <?= htmlspecialchars($club['telefono'] ?? 'No especificado') ?></p>
                    <p class="mb-0"><i class="bi bi-envelope-fill text-success me-2"></i> <?= htmlspecialchars($club['correo'] ?? 'No especificado') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ENTRENADORES Y ATLETAS -->
    <div class="col-lg-8">
        
        <!-- ENTRENADORES -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-3"><i class="bi bi-person-badge text-warning me-2"></i> Entrenadores del Club</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($entrenadores as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['cedula_entrenador']) ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($e['pnombre_entrenador'] . ' ' . $e['apellido_entrenador']) ?></td>
                                <td><?= htmlspecialchars($e['telefono'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($e['correo'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($entrenadores)): ?>
                            <tr><td colspan="4" class="text-muted text-center py-4"><i class="bi bi-person-x fs-4 d-block mb-2 text-secondary"></i> Sin entrenadores asignados</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ATLETAS -->
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-3"><i class="bi bi-person-arms-up text-danger me-2"></i> Atletas del Club</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th class="text-center">Nivel</th>
                                <th class="text-center">Récord (V - D - E)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($atletas as $a): 
                                $nivel = $a['nivel_calculado'] ?? 'Novato';
                                $color = $nivelColor($nivel);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($a['cedula']) ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($a['pnombre'] . ' ' . $a['papellido']) ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $color ?> rounded-pill px-3"><?= htmlspecialchars($nivel) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-75 rounded-pill px-2" title="Victorias"><?= (int)$a['victorias'] ?> V</span>
                                    <span class="badge bg-danger bg-opacity-75 rounded-pill px-2" title="Derrotas"><?= (int)$a['derrotas'] ?> D</span>
                                    <span class="badge bg-secondary bg-opacity-75 rounded-pill px-2" title="Empates"><?= (int)$a['empates'] ?> E</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($atletas)): ?>
                            <tr><td colspan="4" class="text-muted text-center py-4"><i class="bi bi-people fs-4 d-block mb-2 text-secondary"></i> Sin atletas en este club</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>