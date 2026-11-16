<?php $rol = $_SESSION['usuario']['rol'] ?? 'Consulta'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2><i class="bi bi-person-check"></i> Jueces</h2>
    <?php if($rol === 'Administrador'): ?>
        <a href="?c=jueces&a=form" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Juez
        </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pos = 1;
                    foreach($jueces as $j): 
                    ?>
                    <tr>
                        <td><?= $pos++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-badge me-2 text-primary"></i>
                                <?= htmlspecialchars($j['pnombre'] . ' ' . $j['papellido']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($j['cedula'] ?? '-') ?></td>
                        <td>
                            <?php if($j['activo']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <?php if($rol === 'Administrador'): ?>
                                    <a class="btn btn-sm btn-warning" href="?c=jueces&a=form&id=<?= $j['id_juez'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if($j['activo']): ?>
                                        <a class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('¿Desactivar este juez?')" 
                                           href="?c=jueces&a=desactivar&id=<?= $j['id_juez'] ?>">
                                            <i class="bi bi-power"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-sm btn-outline-success" 
                                           onclick="return confirm('¿Activar este juez?')" 
                                           href="?c=jueces&a=activar&id=<?= $j['id_juez'] ?>">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar este juez? No podrá ser usado en combates futuros.')" 
                                       href="?c=jueces&a=eliminar&id=<?= $j['id_juez'] ?>">
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

<?php if(empty($jueces)): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No hay jueces registrados. 
    <?php if($rol === 'Administrador'): ?>
        <a href="?c=jueces&a=form">Registra el primero</a>
    <?php endif; ?>
</div>
<?php endif; ?>