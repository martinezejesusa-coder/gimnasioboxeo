<?php 
$rol = $_SESSION['usuario']['rol'] ?? '';
$idActual = (int)($_SESSION['usuario']['id_usuario'] ?? 0);
?>

<div class="d-flex justify-content-between mb-3">
    <h2><i class="bi bi-people-fill"></i> Gestión de Usuarios</h2>
    <a href="?c=usuarios&a=form" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Usuario
    </a>
</div>

<div class="alert alert-info py-2">
    <small>
        <i class="bi bi-shield-check"></i>
        <strong>Nota:</strong> No puedes eliminar/desactivar tu propio usuario ni el último Administrador activo del sistema.
    </small>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): 
                        $esMiUsuario = ($u['id_usuario'] == $idActual);
                    ?>
                    <tr>
                        <td><?= $u['id_usuario'] ?></td>
                        <td>
                            <?= htmlspecialchars($u['username']) ?>
                            <?php if ($esMiUsuario): ?>
                                <span class="badge bg-primary ms-1">Tú</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $colorRol = $u['rol'] === 'Administrador' ? 'danger' :
                                       ($u['rol'] === 'Entrenador' ? 'warning' : 'secondary');
                            ?>
                            <span class="badge bg-<?= $colorRol ?>">
                                <?php if ($u['rol'] === 'Administrador'): ?>👑<?php endif; ?>
                                <?php if ($u['rol'] === 'Entrenador'): ?>🥊<?php endif; ?>
                                <?php if ($u['rol'] === 'Consulta'): ?>👁️<?php endif; ?>
                                <?= htmlspecialchars($u['rol']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if($u['activo']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-sm btn-warning" href="?c=usuarios&a=form&id=<?= $u['id_usuario'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <?php if (!$esMiUsuario): ?>
                                    <?php if($u['activo']): ?>
                                        <a class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('¿Desactivar al usuario &quot;<?= htmlspecialchars($u['username']) ?>&quot;?')" 
                                           href="?c=usuarios&a=desactivar&id=<?= $u['id_usuario'] ?>">
                                            <i class="bi bi-power"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-sm btn-outline-success" 
                                           onclick="return confirm('¿Activar al usuario &quot;<?= htmlspecialchars($u['username']) ?>&quot;?')" 
                                           href="?c=usuarios&a=activar&id=<?= $u['id_usuario'] ?>">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                    <?php endif; ?>
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