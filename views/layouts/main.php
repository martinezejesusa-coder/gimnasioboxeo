<?php /** Layout principal del sistema */ $rol = $_SESSION['usuario']['rol'] ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Gimnasio de Boxeo Omar Catari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <link href="<?= BASE_URL ?>public/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>public/css/validaciones.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ============================================================ -->
<!-- SIDEBAR (ESCRITORIO)                                         -->
<!-- ============================================================ -->
<nav class="sidebar d-none d-lg-flex flex-column">
    <div class="brand">
        <h4><i class="bi bi-trophy-fill"></i> GIMNASIO BOXEO</h4>
        <small>Sistema Omar Catari</small>
    </div>
    <ul class="nav flex-column mt-3 flex-grow-1">
        <li><a class="nav-link" href="?c=dashboard&a=index"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a class="nav-link" href="?c=torneos&a=index"><i class="bi bi-trophy"></i> Torneos</a></li>
        <?php if ($rol === 'Administrador'): ?>
        <li><a class="nav-link" href="?c=usuarios&a=index"><i class="bi bi-people-fill"></i> Usuarios</a></li>
        <?php endif; ?>
        <li><a class="nav-link" href="?c=entrenadores&a=index"><i class="bi bi-person-badge"></i> Entrenadores</a></li>
        <li><a class="nav-link" href="?c=clubes&a=index"><i class="bi bi-building"></i> Clubes</a></li>
        <li><a class="nav-link" href="?c=atletas&a=index"><i class="bi bi-person-arms-up"></i> Atletas</a></li>
        <li>
            <a class="nav-link" href="?c=combates&a=index">
                <i class="bi bi-fire"></i> Combates
                <span id="notificaciones-badge" class="badge bg-danger rounded-pill ms-auto" style="display:none;">0</span>
            </a>
        </li>
        <?php if (in_array($rol, ['Administrador','Entrenador'])): ?>
        <li><a class="nav-link" href="?c=combates&a=sugerir"><i class="bi bi-shuffle"></i> Emparejamiento</a></li>
        <?php endif; ?>
        <?php if ($rol === 'Administrador'): ?>
        <li><a class="nav-link" href="?c=jueces&a=index"><i class="bi bi-person-check"></i> Jueces</a></li>
        <li><a class="nav-link" href="?c=categorias&a=index"><i class="bi bi-tags"></i> Categorías</a></li>
        <?php endif; ?>
        <li><a class="nav-link" href="?c=reportes&a=index"><i class="bi bi-file-earmark-bar-graph"></i> Reportes</a></li>
        <li class="mt-auto mb-3"><a class="nav-link text-warning" href="?c=auth&a=logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
    </ul>
</nav>

<!-- ============================================================ -->
<!-- OFFCANVAS (MÓVIL Y TABLET)                                   -->
<!-- ============================================================ -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMenu" style="width: 280px;">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title"><i class="bi bi-trophy-fill text-warning"></i> Boxeo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column">
            <li><a class="nav-link text-white" href="?c=dashboard&a=index"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a class="nav-link text-white" href="?c=torneos&a=index"><i class="bi bi-trophy"></i> Torneos</a></li>
            <?php if ($rol === 'Administrador'): ?>
            <li><a class="nav-link text-white" href="?c=usuarios&a=index"><i class="bi bi-people-fill"></i> Usuarios</a></li>
            <?php endif; ?>
            <li><a class="nav-link text-white" href="?c=entrenadores&a=index"><i class="bi bi-person-badge"></i> Entrenadores</a></li>
            <li><a class="nav-link text-white" href="?c=clubes&a=index"><i class="bi bi-building"></i> Clubes</a></li>
            <li><a class="nav-link text-white" href="?c=atletas&a=index"><i class="bi bi-person-arms-up"></i> Atletas</a></li>
            <li><a class="nav-link text-white" href="?c=combates&a=index"><i class="bi bi-fire"></i> Combates</a></li>
            <?php if (in_array($rol, ['Administrador','Entrenador'])): ?>
            <li><a class="nav-link text-white" href="?c=combates&a=sugerir"><i class="bi bi-shuffle"></i> Emparejamiento</a></li>
            <?php endif; ?>
            <?php if ($rol === 'Administrador'): ?>
            <li><a class="nav-link text-white" href="?c=jueces&a=index"><i class="bi bi-person-check"></i> Jueces</a></li>
            <li><a class="nav-link text-white" href="?c=categorias&a=index"><i class="bi bi-tags"></i> Categorías</a></li>
            <?php endif; ?>
            <li><a class="nav-link text-white" href="?c=reportes&a=index"><i class="bi bi-file-earmark-bar-graph"></i> Reportes</a></li>
            <li class="mt-3 border-top border-secondary pt-3"><a class="nav-link text-warning" href="?c=auth&a=logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
        </ul>
    </div>
</div>

<!-- ============================================================ -->
<!-- CONTENIDO PRINCIPAL                                          -->
<!-- ============================================================ -->
<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-dark d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <strong class="text-dark"><?= htmlspecialchars(($_GET['c'] ?? '') ? ucfirst($_GET['c']) : 'Dashboard') ?></strong>
        </div>
        <div class="user-info d-flex align-items-center gap-2">
            <i class="bi bi-person-circle fs-5"></i>
            <span class="d-none d-sm-inline"><?= htmlspecialchars($_SESSION['usuario']['username'] ?? '') ?></span>
            <span class="badge-rol"><?= htmlspecialchars($rol) ?></span>
        </div>
    </header>

    <main class="content-area">
        <?php if (!empty($_SESSION['ok'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><?= htmlspecialchars($_SESSION['ok']) ?>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['ok']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><?= htmlspecialchars($_SESSION['error']) ?>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?= $contenido ?>
    </main>
</div>

<!-- ============================================================ -->
<!-- SCRIPTS BASE                                                 -->
<!-- ============================================================ -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- Validación Global -->
<script src="<?= BASE_URL ?>public/js/validaciones.js"></script>

<!-- Scripts específicos del módulo (se inyectan desde el controlador) -->
<?php if (!empty($scriptsModulo)): ?>
    <?php foreach ($scriptsModulo as $script): ?>
        <script src="<?= BASE_URL . $script ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- DataTables Configuración Global -->
<script>
    $(function() {
        $('.datatable').each(function() {
            var $table = $(this);
            var orderAttr = $table.attr('data-order');
            var orderConfig = [];

            if (orderAttr) {
                try {
                    orderConfig = JSON.parse(orderAttr);
                } catch (e) {
                    console.warn('data-order inválido en tabla:', e);
                    orderConfig = [];
                }
            }

            $table.DataTable({
                dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" +
                     "<'row'<'col-12'tr>>" +
                     "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    { extend: 'copy', text: '<i class="bi bi-files"></i> Copiar', className: 'btn btn-sm btn-outline-dark' },
                    { extend: 'excel', text: '<i class="bi bi-file-earmark-excel"></i> Excel', className: 'btn btn-sm btn-outline-success' },
                    { extend: 'pdf', text: '<i class="bi bi-file-earmark-pdf"></i> PDF', className: 'btn btn-sm btn-outline-danger' },
                    { extend: 'print', text: '<i class="bi bi-printer"></i> Imprimir', className: 'btn btn-sm btn-outline-primary' }
                ],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                responsive: true,
                autoWidth: false,
                order: orderConfig,
                initComplete: function() {
                    var api = this.api();
                    $(api.table().container()).find('input[type="search"]')
                        .removeClass('form-control-sm').addClass('form-control')
                        .attr('placeholder', '🔍 Buscar...');
                }
            });
        });
    });
</script>

<!-- ============================================================ -->
<!-- SISTEMA DE NOTIFICACIONES TOAST                              -->
<!-- ============================================================ -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="liveToast" class="toast border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header" id="toastHeader">
            <i class="bi bi-bell-fill me-2" id="toastIcon"></i>
            <strong class="me-auto" id="toastTitle">Notificación</strong>
            <small id="toastTime">Ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastBody">Mensaje de notificación</div>
    </div>
</div>

<script>
    function mostrarNotificacion(mensaje, tipo, titulo) {
        titulo = titulo || 'Notificación';
        const toast = document.getElementById('liveToast');
        const toastBody = document.getElementById('toastBody');
        const toastTitle = document.getElementById('toastTitle');
        const toastIcon = document.getElementById('toastIcon');
        const toastTime = document.getElementById('toastTime');
        const toastHeader = document.getElementById('toastHeader');

        const colores = {
            'success': { bg: 'bg-success', icon: 'bi-check-circle-fill', color: 'text-white' },
            'warning': { bg: 'bg-warning', icon: 'bi-exclamation-triangle-fill', color: 'text-dark' },
            'danger':  { bg: 'bg-danger',  icon: 'bi-exclamation-circle-fill', color: 'text-white' },
            'info':    { bg: 'bg-info',    icon: 'bi-info-circle-fill', color: 'text-white' },
            'primary': { bg: 'bg-primary', icon: 'bi-bell-fill', color: 'text-white' },
            'default': { bg: 'bg-secondary', icon: 'bi-bell-fill', color: 'text-white' }
        };

        const config = colores[tipo] || colores['default'];
        toastHeader.className = 'toast-header ' + config.bg + ' ' + config.color + ' border-0';
        toastIcon.className = 'bi ' + config.icon + ' me-2';
        toastTitle.textContent = titulo;
        toastBody.textContent = mensaje;
        toastTime.textContent = new Date().toLocaleTimeString();

        const toastInstance = new bootstrap.Toast(toast, { delay: 5000 });
        toastInstance.show();
    }
</script>

</body>
</html>