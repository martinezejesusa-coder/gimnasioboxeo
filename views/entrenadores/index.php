<?php 
$rol = $_SESSION['usuario']['rol'] ?? 'Consulta';
$modeloEnt = new Entrenador();
$clubesPorEntrenador = [];
foreach ($entrenadores as $e) {
    $clubesPorEntrenador[$e['id_entrenador']] = $modeloEnt->clubesDe((int)$e['id_entrenador']);
}
?>

<div class="d-flex justify-content-between mb-3">
    <h2><i class="bi bi-person-badge"></i> Entrenadores</h2>
    <?php if($rol !== 'Consulta'): ?>
        <a href="?c=entrenadores&a=form" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Entrenador
        </a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive" style="min-height: 400px;">
            <table class="table table-hover datatable mb-0" style="min-width: 800px;">
                <thead class="table-light">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Clubes</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($entrenadores as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['cedula_entrenador']) ?></td>
                        <td><?= htmlspecialchars($e['pnombre_entrenador'] . ' ' . $e['apellido_entrenador']) ?></td>
                        <td><?= htmlspecialchars($e['telefono'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($e['correo'] ?? '-') ?></td>
                        <td>
                            <?php 
                            $idsClubes = $clubesPorEntrenador[$e['id_entrenador']] ?? [];
                            if (!empty($idsClubes)) {
                                echo '<div class="d-flex flex-wrap gap-1">';
                                foreach ($clubes as $club) {
                                    if (in_array($club['id_club'], $idsClubes)) {
                                        echo '<span class="badge bg-secondary">' . htmlspecialchars($club['nombre_club']) . '</span>';
                                    }
                                }
                                echo '</div>';
                            } else {
                                echo '<span class="text-muted small">Sin clubes</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php if (!empty($e['activo'])): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1 flex-wrap">
                                <?php if($rol !== 'Consulta'): ?>
                                    <a class="btn btn-sm btn-warning" href="?c=entrenadores&a=form&id=<?= $e['id_entrenador'] ?>" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if($rol === 'Administrador'): ?>
                                    <a class="btn btn-sm btn-outline-danger" title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar a <?= htmlspecialchars($e['pnombre_entrenador'] . ' ' . $e['apellido_entrenador']) ?>?')" 
                                       href="?c=entrenadores&a=eliminar&id=<?= $e['id_entrenador'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(empty($entrenadores)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No hay entrenadores registrados. 
    <?php if($rol !== 'Consulta'): ?>
        <a href="?c=entrenadores&a=form">Registra el primero</a>
    <?php endif; ?>
</div>
<?php endif; ?>