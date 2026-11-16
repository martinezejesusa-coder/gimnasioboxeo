<?php
$combate = $combate ?? [];
$errores = $errores ?? [];
$tarjetas = $tarjetas ?? [];
$isEdit = !empty($combate['id_combate']);
$opcionesJuecesHtml = '';
foreach ($jueces as $j) {
    $nombre = htmlspecialchars($j['pnombre'] . ' ' . $j['papellido'], ENT_QUOTES, 'UTF-8');
    $opcionesJuecesHtml .= '<option value="' . (int)$j['id_juez'] . '">' . $nombre . '</option>';
}
$dataJs = json_encode([
    'opcionesJueces' => $opcionesJuecesHtml,
    'totalTarjetas' => count($tarjetas)
]);
?>

<?= renderErroresGenerales($errores) ?>

<h2><?= $isEdit ? '✏️ Editar Combate' : '➕ Registrar Combate' ?></h2>
<p class="text-muted">Completa la información del combate, incluyendo los detalles de la pelea y las tarjetas de puntuación.</p>

<div class="card">
    <div class="card-body">
        <form method="POST" action="?c=combates&a=guardar" id="formCombate" data-ajax data-validate novalidate>
            <input type="hidden" name="id" value="<?= old('id', $combate['id_combate'] ?? '') ?>">

            <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información Básica del Combate</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_torneo" class="form-label">🏟️ Torneo</label>
                            <?php $torneoSel = old('id_torneo', $combate['id_torneo'] ?? ''); ?>
                            <select name="id_torneo" id="id_torneo" class="<?= fieldClassSelect('id_torneo', $errores) ?>">
                                <option value="">-- Sin torneo --</option>
                                <?php foreach($torneos as $t): ?>
                                    <option value="<?= $t['id_torneo'] ?>" <?= ($torneoSel == $t['id_torneo']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t['nombre_torneo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha" class="form-label required">📅 Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="<?= fieldClass('fecha', $errores) ?>" required 
                                   value="<?= old('fecha', $combate['fecha'] ?? date('Y-m-d')) ?>"
                                   data-msg-required="La fecha del combate es obligatoria">
                        </div>
                        <div class="col-md-3">
                            <label for="ronda" class="form-label required">🔢 Ronda en llave</label>
                            <input type="number" min="1" name="ronda" id="ronda" class="<?= fieldClass('ronda', $errores) ?>" required 
                                   value="<?= old('ronda', $combate['ronda'] ?? 1) ?>"
                                   data-msg-required="La ronda es obligatoria">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: ATLETAS -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-person-arms-up"></i> Atletas Participantes</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_atleta_local" class="form-label required">🥊 Atleta Local</label>
                            <?php $localSel = old('id_atleta_local', $combate['id_atleta_local'] ?? ''); ?>
                            <select name="id_atleta_local" id="id_atleta_local" class="<?= fieldClassSelect('id_atleta_local', $errores) ?>" required
                                    data-msg-required="Debes seleccionar el atleta local"
                                    data-differs-from="id_atleta_visitante"
                                    data-msg-differs="El atleta local no puede ser el mismo que el visitante">
                                <option value="">-- Seleccionar atleta local --</option>
                                <?php foreach($atletas as $a): 
                                    $texto = $a['pnombre'] . ' ' . $a['papellido'] . ' (' . $a['cedula'] . ')';
                                    if (!empty($a['nombre_club'])) $texto .= ' — ' . $a['nombre_club'];
                                ?>
                                    <option value="<?= $a['id_atleta'] ?>" <?= ($localSel == $a['id_atleta']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($texto) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback d-block"><?= errorMsg('id_atleta_local', $errores) ?></div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_atleta_visitante" class="form-label required">✈️ Atleta Visitante</label>
                            <?php $visitSel = old('id_atleta_visitante', $combate['id_atleta_visitante'] ?? ''); ?>
                            <select name="id_atleta_visitante" id="id_atleta_visitante" class="<?= fieldClassSelect('id_atleta_visitante', $errores) ?>" required
                                    data-msg-required="Debes seleccionar el atleta visitante"
                                    data-differs-from="id_atleta_local"
                                    data-msg-differs="El atleta visitante no puede ser el mismo que el local">
                                <option value="">-- Seleccionar atleta visitante --</option>
                                <?php foreach($atletas as $a): 
                                    $texto = $a['pnombre'] . ' ' . $a['papellido'] . ' (' . $a['cedula'] . ')';
                                    if (!empty($a['nombre_club'])) $texto .= ' — ' . $a['nombre_club'];
                                ?>
                                    <option value="<?= $a['id_atleta'] ?>" <?= ($visitSel == $a['id_atleta']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($texto) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback d-block"><?= errorMsg('id_atleta_visitante', $errores) ?></div>
                        </div>
                        <div class="col-12" id="mismoAtletaWarning" style="display: none;">
                            <div class="alert alert-danger py-2">
                                <i class="bi bi-exclamation-triangle"></i> El atleta local y visitante no pueden ser el mismo.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: DETALLES -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-clipboard-data"></i> Detalles de la Pelea</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="rounds_pautados" class="form-label">⏱️ Asaltos pactados</label>
                            <?php $roundsSel = old('rounds_pautados', $combate['rounds_pautados'] ?? 3); ?>
                            <select name="rounds_pautados" id="rounds_pautados" class="<?= fieldClassSelect('rounds_pautados', $errores) ?>">
                                <?php foreach([3, 6, 8, 10, 12] as $r): ?>
                                    <option value="<?= $r ?>" <?= ($roundsSel == $r) ? 'selected' : '' ?>><?= $r ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="roundFinalInput" class="form-label">🏁 Asalto de finalización</label>
                            <input type="number" name="round_finalizacion" class="<?= fieldClass('round_finalizacion', $errores) ?>" 
                                   min="1" placeholder="Opcional" id="roundFinalInput"
                                   value="<?= old('round_finalizacion', $combate['round_finalizacion'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="decisionSelect" class="form-label">🏆 Decisión de victoria</label>
                            <?php $decSel = old('decision_victoria', $combate['decision_victoria'] ?? ''); ?>
                            <select name="decision_victoria" id="decisionSelect" class="<?= fieldClassSelect('decision_victoria', $errores) ?>">
                                <option value="">-- Seleccionar --</option>
                                <optgroup label="━━━ Decisiones por Puntuación ━━━">
                                    <option value="UD" <?= ($decSel == 'UD') ? 'selected' : '' ?>>UD - Decisión Unánime</option>
                                    <option value="SD" <?= ($decSel == 'SD') ? 'selected' : '' ?>>SD - Decisión Dividida</option>
                                    <option value="MD" <?= ($decSel == 'MD') ? 'selected' : '' ?>>MD - Decisión Mayoritaria</option>
                                    <option value="UED" <?= ($decSel == 'UED') ? 'selected' : '' ?>>Empate Unánime</option>
                                    <option value="SED" <?= ($decSel == 'SED') ? 'selected' : '' ?>>Empate Dividido</option>
                                    <option value="MED" <?= ($decSel == 'MED') ? 'selected' : '' ?>>Empate Mayoritario</option>
                                </optgroup>
                                <optgroup label="━━━ Vía Rápida ━━━">
                                    <option value="KO" <?= ($decSel == 'KO') ? 'selected' : '' ?>>KO - Nocaut</option>
                                    <option value="TKO" <?= ($decSel == 'TKO') ? 'selected' : '' ?>>TKO - Nocaut Técnico</option>
                                    <option value="RTD" <?= ($decSel == 'RTD') ? 'selected' : '' ?>>RTD - Retiro en Esquina</option>
                                </optgroup>
                                <optgroup label="━━━ Técnicas ━━━">
                                    <option value="TD" <?= ($decSel == 'TD') ? 'selected' : '' ?>>TD - Decisión Técnica</option>
                                    <option value="TED" <?= ($decSel == 'TED') ? 'selected' : '' ?>>Empate Técnico</option>
                                </optgroup>
                                <optgroup label="━━━ Especiales ━━━">
                                    <option value="DQ" <?= ($decSel == 'DQ') ? 'selected' : '' ?>>DQ - Descalificación</option>
                                    <option value="NC" <?= ($decSel == 'NC') ? 'selected' : '' ?>>NC - Sin Decisión</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="posicion_llave" class="form-label">📊 Posición en llave</label>
                            <input type="number" name="posicion_llave" id="posicion_llave" class="<?= fieldClass('posicion_llave', $errores) ?>" 
                                   min="0" placeholder="Opcional" value="<?= old('posicion_llave', $combate['posicion_llave'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="resultadoSelect" class="form-label required">🎯 Resultado</label>
                            <?php $resSel = old('resultado', $combate['resultado'] ?? 'Pendiente'); ?>
                            <select name="resultado" id="resultadoSelect" class="<?= fieldClassSelect('resultado', $errores) ?>" required>
                                <option value="Pendiente" <?= ($resSel == 'Pendiente') ? 'selected' : '' ?>>⏳ Pendiente</option>
                                <option value="Local" <?= ($resSel == 'Local') ? 'selected' : '' ?>>🥊 Gana Local</option>
                                <option value="Visitante" <?= ($resSel == 'Visitante') ? 'selected' : '' ?>>✈️ Gana Visitante</option>
                                <option value="Empate" <?= ($resSel == 'Empate') ? 'selected' : '' ?>>🤝 Empate</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 4: LLAVES -->
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-diagram-3"></i> Estructura de la Llave del Torneo</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2 text-center">
                        <div class="col-md-3"><div class="border rounded p-3 bg-light h-100"><span class="badge bg-secondary fs-6">8</span><div class="fw-bold mt-1">Octavos</div><small class="text-muted">Ronda 1</small><hr class="my-2"><div class="small text-start"><span class="badge bg-secondary me-1">1</span> Combate 1<br><span class="badge bg-secondary me-1">2</span> Combate 2<br><span class="badge bg-secondary me-1">3</span> Combate 3<br><span class="badge bg-secondary me-1">4</span> Combate 4</div></div></div>
                        <div class="col-md-3"><div class="border rounded p-3 bg-light h-100"><span class="badge bg-primary fs-6">4</span><div class="fw-bold mt-1">Cuartos</div><small class="text-muted">Ronda 2</small><hr class="my-2"><div class="small text-start"><span class="badge bg-primary me-1">1</span> Combate 1<br><span class="badge bg-primary me-1">2</span> Combate 2</div></div></div>
                        <div class="col-md-3"><div class="border rounded p-3 bg-light h-100"><span class="badge bg-warning text-dark fs-6">2</span><div class="fw-bold mt-1">Semifinal</div><small class="text-muted">Ronda 3</small><hr class="my-2"><div class="small text-start"><span class="badge bg-warning text-dark me-1">1</span> Combate 1</div></div></div>
                        <div class="col-md-3"><div class="border rounded p-3 bg-danger text-white h-100"><span class="badge bg-light text-dark fs-6">1</span><div class="fw-bold mt-1">🏆 Final</div><small>Ronda 4</small><hr class="my-2 border-light"><div class="small text-start"><span class="badge bg-light text-dark me-1">1</span> 🏆 Campeón</div></div></div>
                    </div>
                    <div class="mt-3 p-2 bg-info bg-opacity-10 rounded">
                        <small class="text-muted">
                            <i class="bi bi-lightbulb text-warning"></i>
                            <strong>¿Cómo se usa?</strong>
                            <span class="d-block mt-1">• <strong>Ronda 1</strong> = Octavos &nbsp;|&nbsp; <strong>Ronda 2</strong> = Cuartos</span>
                            <span class="d-block">• <strong>Ronda 3</strong> = Semifinal &nbsp;|&nbsp; <strong>Ronda 4</strong> = Final</span>
                            <span class="d-block">• <strong>Posición</strong> = orden dentro de la ronda (1, 2, 3...)</span>
                        </small>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 5: TARJETAS -->
            <div class="card mb-4 border-success" id="seccionTarjetas">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-clipboard-data"></i> Tarjetas de Puntuación</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Registra los puntajes de cada juez por asalto. Los puntajes van de 6 a 10.</p>
                    <?php if (!empty($errores['tarjetas'])): ?>
                        <div id="avisoTarjetasPendiente" class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($errores['tarjetas']) ?></div>
                    <?php endif; ?>
                    <div id="tarjetas-container">
                        <?php if (!empty($tarjetas)): ?>
                            <?php foreach($tarjetas as $idx => $t): ?>
                                <div class="row g-2 mb-2 tarjeta-row" data-index="<?= $idx ?>">
                                    <div class="col-md-2">
                                        <label class="form-label small">👨‍⚖️ Juez</label>
                                        <select name="tarjetas[<?= $idx ?>][id_juez]" class="form-select form-select-sm">
                                            <option value="">-- Seleccionar --</option>
                                            <?php foreach($jueces as $j): ?>
                                                <option value="<?= $j['id_juez'] ?>" <?= ($t['id_juez'] == $j['id_juez']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($j['pnombre'] . ' ' . $j['papellido']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2"><label class="form-label small">🔢 Round</label><input type="number" name="tarjetas[<?= $idx ?>][numero_round]" class="form-control form-control-sm" min="1" value="<?= $t['numero_round'] ?>"></div>
                                    <div class="col-md-2"><label class="form-label small text-success">🥊 Local</label><input type="number" name="tarjetas[<?= $idx ?>][puntos_local]" class="form-control form-control-sm" min="6" max="10" value="<?= $t['puntos_local'] ?>"></div>
                                    <div class="col-md-2"><label class="form-label small text-danger">✈️ Visitante</label><input type="number" name="tarjetas[<?= $idx ?>][puntos_visitante]" class="form-control form-control-sm" min="6" max="10" value="<?= $t['puntos_visitante'] ?>"></div>
                                    <div class="col-md-3 d-flex align-items-end"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.tarjeta-row').remove()"><i class="bi bi-trash"></i> Eliminar</button></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info text-center py-3"><i class="bi bi-info-circle"></i> No hay tarjetas registradas.</div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success mt-2" id="agregarTarjeta"><i class="bi bi-plus-circle"></i> Agregar tarjeta</button>
                </div>
            </div>

            <!-- BOTONES -->
            <div class="d-flex gap-2 justify-content-end">
                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</button>
                <a href="?c=combates&a=index" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary" disabled><i class="bi bi-save"></i> Guardar Combate</button>
            </div>
        </form>
    </div>
</div>

<!-- Datos para el módulo de Combates (JS) -->
<script>
    window.COMBATES_DATA = <?= $dataJs ?>;
</script>
