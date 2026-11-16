<?php $rol=$_SESSION['usuario']['rol']; ?>
<div class="d-flex justify-content-between mb-3">
  <h2><i class="bi bi-person-arms-up"></i> Atletas</h2>
  <?php if($rol!=='Consulta'): ?><a href="?c=atletas&a=form" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo Atleta</a><?php endif; ?>
</div>
<form class="card card-body mb-3" method="GET">
<input type="hidden" name="c" value="atletas"><input type="hidden" name="a" value="index">
<div class="row g-2">
<div class="col-md-4"><select name="club" class="form-select"><option value="">-- Club --</option>
<?php foreach($clubes as $c): ?><option value="<?=$c['id_club']?>" <?= $filtros['club']==$c['id_club']?'selected':'' ?>><?=htmlspecialchars($c['nombre_club'])?></option><?php endforeach; ?>
</select></div>
<div class="col-md-4"><select name="categoria" class="form-select"><option value="">-- Categoría --</option>
<?php foreach($categorias as $c): ?><option value="<?=$c['id_categoria']?>" <?= $filtros['categoria']==$c['id_categoria']?'selected':'' ?>><?=$c['nombre_categoria']?></option><?php endforeach; ?>
</select></div>
<div class="col-md-3"><select name="activo" class="form-select"><option value="">-- Estado --</option>
<option value="1" <?= $filtros['activo']==='1'?'selected':'' ?>>Activos</option>
<option value="0" <?= $filtros['activo']==='0'?'selected':'' ?>>Inactivos</option>
</select></div>
<div class="col-md-1"><button class="btn btn-dark w-100"><i class="bi bi-funnel"></i></button></div>
</div></form>
<div class="card"><div class="card-body">
<table class="table table-hover datatable">
<thead class="table-dark"><tr><th></th><th>Cédula</th><th>Nombre</th><th>Club</th><th>Categoría</th><th>Nivel</th><th>Récord</th><th></th></tr></thead>
<tbody>
<?php foreach($atletas as $a): ?>
<tr><td><?php if($a['foto']): ?><img src="<?=BASE_URL.$a['foto']?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover"><?php else: ?><i class="bi bi-person-circle" style="font-size:2rem"></i><?php endif; ?></td>
<td><?=$a['cedula']?></td>
<td><?=htmlspecialchars($a['pnombre'].' '.$a['papellido'])?></td>
<td><?=htmlspecialchars($a['nombre_club'] ?? '-')?></td>
<td><?=htmlspecialchars($a['nombre_categoria'] ?? '-')?></td>
<td><span class="nivel-badge nivel-<?=$a['nivel_calculado']?>"><?=$a['nivel_calculado']?></span></td>
<td><strong class="text-success"><?=$a['victorias']?>V</strong> - <strong class="text-danger"><?=$a['derrotas']?>D</strong> - <?=$a['empates']?>E</td>
<td><a class="btn btn-sm btn-dark" href="?c=atletas&a=ver&id=<?=$a['id_atleta']?>"><i class="bi bi-eye"></i></a>
<?php if($rol!=='Consulta'): ?><a class="btn btn-sm btn-warning" href="?c=atletas&a=form&id=<?=$a['id_atleta']?>"><i class="bi bi-pencil"></i></a><?php endif; ?>
<?php if($rol==='Administrador'): ?>
<a class="btn btn-sm btn-outline-danger" 
   onclick="return confirm('¿Desactivar a <?= htmlspecialchars($a['pnombre'].' '.$a['papellido']) ?>?')" 
   href="?c=atletas&a=eliminar&id=<?=$a['id_atleta']?>">
    <i class="bi bi-power"></i>
</a>
<?php endif; ?>
</td></tr>
<?php endforeach; ?>
</tbody></table>
</div></div>