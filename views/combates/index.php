<?php
/**
 * Vista: Listado de Combates 
 */
$rol = $_SESSION['usuario']['rol'] ?? 'Consulta';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0"><i class="bi bi-fire text-danger"></i> Combates</h2>
    <div class="d-flex gap-2 flex-wrap">
        <?php if($rol !== 'Consulta'): ?>
            <a href="?c=combates&a=sugerir" class="btn btn-warning">
                <i class="bi bi-shuffle"></i> Emparejamiento
            </a>
            <a href="?c=combates&a=form" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo Combate
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table id="tablaCombates" class="table table-hover align-middle mb-0" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Torneo</th>
                    <th>Local</th>
                    <th>Visitante</th>
                    <th class="text-center">Ronda</th>
                    <th class="text-center">Decisión</th>
                    <th class="text-center">Resultado</th>
                    <th class="text-center" style="width: 140px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    window.ROL_USUARIO = '<?= htmlspecialchars($rol) ?>';
</script>