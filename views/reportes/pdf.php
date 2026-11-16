<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Gimnasio Boxeo Omar Catari</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; padding: 30px; color: #333; background: #fff; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #c8102e; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #c8102e; font-size: 24px; font-weight: 700; }
        .header .fecha { color: #666; font-size: 14px; }
        .subtitle { background: #f8f9fa; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
        .subtitle strong { color: #c8102e; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .stats .stat { background: #f1f3f5; padding: 8px 15px; border-radius: 5px; font-size: 13px; }
        .stats .stat .num { font-weight: 700; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        thead th { background: #1a1a1a; color: #fff; padding: 10px 12px; text-align: left; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        tbody td { padding: 8px 12px; border-bottom: 1px solid #e9ecef; }
        tbody tr:hover { background: #f8f9fa; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-primary { background: #cce5ff; color: #004085; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #dee2e6; text-align: center; font-size: 12px; color: #999; }
        .print-btn { background: #c8102e; color: #fff; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-bottom: 20px; font-weight: 600; }
        .print-btn:hover { background: #a00d24; }
        @media print {
            .print-btn { display: none; }
            body { padding: 15px; }
            thead th { background: #333 !important; }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">
        🖨️ Imprimir / Guardar PDF
    </button>

    <div class="header">
        <h1>🥊 Gimnasio de Boxeo Omar Catari</h1>
        <div class="fecha"><?= date('d/m/Y H:i') ?></div>
    </div>

    <div class="subtitle">
        <strong>Reporte:</strong> <?= htmlspecialchars($tituloReporte ?? 'General') ?>
    </div>

    <div class="stats">
        <div class="stat">
            <span class="num"><?= count($datos) ?></span> Atletas
        </div>
        <?php 
        $totalV = 0; $totalD = 0; $totalE = 0; $totalComb = 0;
        foreach ($datos as $a) {
            $totalV += (int)($a['victorias'] ?? 0);
            $totalD += (int)($a['derrotas'] ?? 0);
            $totalE += (int)($a['empates'] ?? 0);
            $totalComb += (int)($a['total_combates'] ?? 0);
        }
        ?>
        <div class="stat">
            <span class="num"><?= $totalV ?></span> Victorias Totales
        </div>
        <div class="stat">
            <span class="num"><?= $totalD ?></span> Derrotas Totales
        </div>
        <div class="stat">
            <span class="num"><?= $totalComb ?></span> Combates Totales
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Club</th>
                <th>Nivel</th>
                <th style="text-align:center;">V</th>
                <th style="text-align:center;">D</th>
                <th style="text-align:center;">E</th>
                <th style="text-align:center;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $pos = 1;
            foreach ($datos as $a): 
                $nivel = $a['nivel_calculado'] ?? 'Novato';
            ?>
            <tr>
                <td><?= $pos++ ?></td>
                <td><?= htmlspecialchars($a['cedula']) ?></td>
                <td><strong><?= htmlspecialchars($a['pnombre'] . ' ' . $a['papellido']) ?></strong></td>
                <td><?= htmlspecialchars($a['nombre_club'] ?? '-') ?></td>
                <td>
                    <span class="badge badge-<?= 
                        $nivel == 'Novato' ? 'secondary' :
                        ($nivel == 'Intermedio' ? 'primary' :
                        ($nivel == 'Avanzado' ? 'warning' : 'danger')) 
                    ?>">
                        <?= htmlspecialchars($nivel) ?>
                    </span>
                </td>
                <td style="text-align:center; color:#28a745; font-weight:700;"><?= (int)($a['victorias'] ?? 0) ?></td>
                <td style="text-align:center; color:#dc3545; font-weight:700;"><?= (int)($a['derrotas'] ?? 0) ?></td>
                <td style="text-align:center;"><?= (int)($a['empates'] ?? 0) ?></td>
                <td style="text-align:center; font-weight:700;"><?= (int)($a['total_combates'] ?? 0) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Reporte generado el <?= date('d/m/Y H:i') ?> | Sistema de Gestión de Gimnasio de Boxeo Omar Catari
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        };
    </script>

</body>
</html>