/* ============================================================
   LÓGICA DEL MÓDULO DE COMBATES 
   ============================================================ */
(function ($) {
    'use strict';

    $(document).ready(function () {
        // Detectar qué página está activa y llamar a la función correspondiente
        if ($('#formCombate').length) {
            initFormCombate();
        }
        if ($('#tablaCombates').length) {
            initTablaCombates();
        }
        if ($('#formSugerir').length) {
            initSugerir();
        }
    });

    // ============================================================
    // PARTE 1: FORMULARIO
    // ============================================================
    function initFormCombate() {
        initTarjetas();
        initRounds();
        initResultado();
        initBloqueoAtletas();
    }

    function initBloqueoAtletas() {
        if (!$.fn.select2) return;

        var $selectLocal = $('#id_atleta_local');
        var $selectVisitante = $('#id_atleta_visitante');
        if (!$selectLocal.length || !$selectVisitante.length) return;

        $selectLocal.select2({
            theme: 'bootstrap-5', width: '100%', language: 'es',
            placeholder: '🔍 Busca por nombre, cédula o club...', allowClear: false,
            templateResult: function (data) {
                if (!data.id) return data.text;
                if ($selectVisitante.val() === data.id) return $('<span>').text('🚫 ' + data.text).css('opacity', 0.55);
                return data.text;
            }
        });
        $selectVisitante.select2({
            theme: 'bootstrap-5', width: '100%', language: 'es',
            placeholder: '🔍 Busca por nombre, cédula o club...', allowClear: false,
            templateResult: function (data) {
                if (!data.id) return data.text;
                if ($selectLocal.val() === data.id) return $('<span>').text('🚫 ' + data.text).css('opacity', 0.55);
                return data.text;
            }
        });

        $selectLocal.on('select2:selecting', function (e) {
            if (e.params.args.data.id === $selectVisitante.val()) {
                e.preventDefault(); e.stopPropagation();
                toastDuplicado('Ese atleta ya está seleccionado como visitante.');
                return false;
            }
        });
        $selectVisitante.on('select2:selecting', function (e) {
            if (e.params.args.data.id === $selectLocal.val()) {
                e.preventDefault(); e.stopPropagation();
                toastDuplicado('Ese atleta ya está seleccionado como local.');
                return false;
            }
        });

        function actualizarOpcionesBloqueadas() {
            var valLocal = $selectLocal.val();
            var valVisitante = $selectVisitante.val();
            $selectLocal.find('option').prop('disabled', false);
            $selectVisitante.find('option').prop('disabled', false);
            if (valVisitante) $selectLocal.find('option[value="' + valVisitante + '"]').prop('disabled', true);
            if (valLocal) $selectVisitante.find('option[value="' + valLocal + '"]').prop('disabled', true);
            $selectLocal.trigger('change.select2');
            $selectVisitante.trigger('change.select2');
        }

        function actualizarAdvertencia() {
            var vL = $selectLocal.val(), vV = $selectVisitante.val();
            if (vL && vV && vL === vV) $('#mismoAtletaWarning').show();
            else $('#mismoAtletaWarning').hide();
        }

        function toastDuplicado(msg) {
            if (typeof mostrarNotificacion === 'function') mostrarNotificacion(msg, 'warning', '⚠️ Atleta duplicado');
        }

        $selectLocal.on('change', function () {
            if ($selectLocal.val() === $selectVisitante.val() && $selectLocal.val() !== '') {
                $selectVisitante.val(null).trigger('change.select2');
                toastDuplicado('El atleta visitante fue limpiado porque ya es el local.');
            }
            actualizarOpcionesBloqueadas();
            actualizarAdvertencia();
            if (typeof window.revalidarCampo === 'function') window.revalidarCampo($selectVisitante.get(0));
        });
        $selectVisitante.on('change', function () {
            if ($selectVisitante.val() === $selectLocal.val() && $selectVisitante.val() !== '') {
                $selectVisitante.val(null).trigger('change.select2');
                toastDuplicado('Ese atleta ya está seleccionado como local.');
            }
            actualizarOpcionesBloqueadas();
            actualizarAdvertencia();
            if (typeof window.revalidarCampo === 'function') window.revalidarCampo($selectLocal.get(0));
        });

        actualizarOpcionesBloqueadas();
        actualizarAdvertencia();
    }

    function initTarjetas() {
        var $btnAgregar = $('#agregarTarjeta');
        if (!$btnAgregar.length) return;

        var opcionesJueces = (window.COMBATES_DATA && window.COMBATES_DATA.opcionesJueces) || '';
        var contadorTarjetas = (window.COMBATES_DATA && window.COMBATES_DATA.totalTarjetas) || 0;

        $btnAgregar.on('click', function () {
            var $container = $('#tarjetas-container');
            $container.find('.alert').remove();

            var index = contadorTarjetas;
            var html =
                '<div class="col-md-2"><label class="form-label small">👨‍⚖️ Juez</label>' +
                '<select name="tarjetas[' + index + '][id_juez]" class="form-select form-select-sm"><option value="">-- Seleccionar --</option>' + opcionesJueces + '</select></div>' +
                '<div class="col-md-2"><label class="form-label small">🔢 Round</label><input type="number" name="tarjetas[' + index + '][numero_round]" class="form-control form-control-sm" min="1" value="1"></div>' +
                '<div class="col-md-2"><label class="form-label small text-success">🥊 Local</label><input type="number" name="tarjetas[' + index + '][puntos_local]" class="form-control form-control-sm" min="6" max="10" value="10"></div>' +
                '<div class="col-md-2"><label class="form-label small text-danger">✈️ Visitante</label><input type="number" name="tarjetas[' + index + '][puntos_visitante]" class="form-control form-control-sm" min="6" max="10" value="9"></div>' +
                '<div class="col-md-3 d-flex align-items-end"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-tarjeta"><i class="bi bi-trash"></i> Eliminar</button></div>';

            $('<div></div>')
                .addClass('row g-2 mb-2 tarjeta-row')
                .attr('data-index', index)
                .html(html)
                .appendTo($container);

            contadorTarjetas++;
            $('#formCombate').trigger('change');
        });

        $(document).on('click', '.btn-remove-tarjeta', function () {
            $(this).closest('.tarjeta-row').remove();
            $('#formCombate').trigger('change');
        });
    }

    function initRounds() {
        var $roundsPautados = $('#rounds_pautados');
        var $roundFinal = $('#roundFinalInput');
        if (!$roundsPautados.length || !$roundFinal.length) return;

        function actualizarMax() { $roundFinal.attr('max', $roundsPautados.val()); }
        $roundsPautados.on('change', actualizarMax);
        actualizarMax();
    }

    function initResultado() {
        var $resultadoSelect = $('#resultadoSelect');
        var $decisionSelect = $('#decisionSelect');
        var $roundFinal = $('#roundFinalInput');
        var $seccionTarjetas = $('#seccionTarjetas');
        var $avisoTarjetas = $('#avisoTarjetasPendiente');
        var $btnAgregar = $('#agregarTarjeta');
        if (!$resultadoSelect.length) return;

        function actualizar() {
            var esPendiente = $resultadoSelect.val() === 'Pendiente';
            if ($decisionSelect.length && $roundFinal.length) {
                if (esPendiente) {
                    $decisionSelect.val('').prop('disabled', true).addClass('bg-light');
                    $roundFinal.val('').prop('disabled', true).addClass('bg-light');
                } else {
                    $decisionSelect.prop('disabled', false).removeClass('bg-light');
                    $roundFinal.prop('disabled', false).removeClass('bg-light');
                }
            }
            if ($seccionTarjetas.length) {
                if (esPendiente) {
                    $avisoTarjetas.show();
                    $btnAgregar.prop('disabled', true).addClass('disabled');
                    $seccionTarjetas.find('input, select').prop('disabled', true);
                    $seccionTarjetas.find('.btn-remove-tarjeta').prop('disabled', true).addClass('disabled');
                } else {
                    $avisoTarjetas.hide();
                    $btnAgregar.prop('disabled', false).removeClass('disabled');
                    $seccionTarjetas.find('input, select').prop('disabled', false);
                    $seccionTarjetas.find('.btn-remove-tarjeta').prop('disabled', false).removeClass('disabled');
                }
            }
        }
        $resultadoSelect.on('change', actualizar);
        actualizar();
    }

    // ============================================================
    // PARTE 2: LISTADO
    // ============================================================
    function initTablaCombates() {
        var rol = window.ROL_USUARIO || 'Consulta';

        $('#tablaCombates').DataTable({
            processing: true, serverSide: true,
            ajax: { url: 'index.php?c=combates&a=ajaxListar', type: 'POST' },
            columns: [
                { data: 'fecha' },
                { data: 'nombre_torneo', defaultContent: '-' },
                { data: 'local_nombre' },
                { data: 'visitante_nombre' },
                { data: 'ronda' },
                { data: 'decision_victoria', defaultContent: '-' },
                { data: 'resultado', render: function (data) {
                    var color = 'secondary';
                    if (data === 'Pendiente') color = 'warning';
                    else if (data === 'Local') color = 'success';
                    else if (data === 'Visitante') color = 'danger';
                    else if (data === 'Empate') color = 'info';
                    return '<span class="badge bg-' + color + ' rounded-pill px-3">' + data + '</span>';
                }},
                { data: 'id_combate', orderable: false, render: function (data) {
                    var botones = '<div class="d-flex justify-content-center gap-1 flex-wrap">';
                    botones += '<a class="btn btn-sm btn-info" href="?c=combates&a=ver&id=' + data + '" title="Ver"><i class="bi bi-eye"></i></a>';
                    if (rol !== 'Consulta') botones += '<a class="btn btn-sm btn-warning" href="?c=combates&a=form&id=' + data + '" title="Editar"><i class="bi bi-pencil"></i></a>';
                    if (rol === 'Administrador') botones += '<a class="btn btn-sm btn-outline-danger" onclick="return confirm(\'¿Eliminar?\')" href="?c=combates&a=eliminar&id=' + data + '" title="Eliminar"><i class="bi bi-trash"></i></a>';
                    botones += '</div>';
                    return botones;
                }}
            ],
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
            order: [[0, 'desc']], pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            responsive: true, autoWidth: false,
            initComplete: function () {
                var api = this.api();
                $(api.table().container()).find('input[type="search"]')
                    .removeClass('form-control-sm').addClass('form-control')
                    .attr('placeholder', '🔍 Buscar...');
            }
        });
    }

    // ============================================================
    // PARTE 3: SUGERIR
    // ============================================================
    function initSugerir() {
        if ($.fn.select2) {
            $('#selectCategoria').select2({
                theme: 'bootstrap-5', width: '100%', language: 'es',
                placeholder: '🔍 Buscar categoría...', allowClear: false
            });
        }

        $('#formSugerir').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $btn = $('#btnSugerir');
            var $resultados = $('#resultadosSugerencias');
            var originalText = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Buscando...');

            $.ajax({
                url: $form.attr('action'), type: 'POST',
                data: $form.serialize(), dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (response) {
                    if (response.status === 'error') {
                        if (typeof mostrarNotificacion === 'function') mostrarNotificacion(response.mensaje, 'danger', 'Error');
                        $resultados.empty();
                    } else {
                        renderizarSugerencias(response.sugerencias);
                    }
                },
                error: function () {
                    if (typeof mostrarNotificacion === 'function') mostrarNotificacion('Error de conexión', 'danger', 'Error');
                },
                complete: function () {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });
    }

    function renderizarSugerencias(sugerencias) {
        var $container = $('#resultadosSugerencias');
        $container.empty();

        if (!sugerencias || sugerencias.length === 0) {
            $container.html(
                '<div class="alert alert-info text-center py-4 border-0 shadow-sm">' +
                    '<i class="bi bi-info-circle fs-3 d-block mb-2"></i>' +
                    '<h5 class="mb-0">No se encontraron atletas elegibles</h5>' +
                    '<p class="text-muted mb-0">Intenta ajustar los filtros.</p>' +
                '</div>'
            );
            return;
        }

        var html = '<div class="row g-4">';
        var baseUrl = window.BASE_URL || '';

        $.each(sugerencias, function (index, s) {
            var fotoLocal = s.local.foto
                ? '<img src="' + baseUrl + s.local.foto + '" class="rounded-circle mb-2 suggestion-photo border-danger">'
                : '<i class="bi bi-person-circle text-danger mb-2 suggestion-icon"></i>';
            var fotoVisitante = s.visitante.foto
                ? '<img src="' + baseUrl + s.visitante.foto + '" class="rounded-circle mb-2 suggestion-photo border-primary">'
                : '<i class="bi bi-person-circle text-primary mb-2 suggestion-icon"></i>';

            html +=
                '<div class="col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body p-4">' +
                    '<div class="row align-items-center">' +
                        '<div class="col-5 text-center">' + fotoLocal +
                            '<h6 class="fw-bold mb-0">' + s.local.pnombre + ' ' + s.local.papellido + '</h6>' +
                            '<small class="text-muted d-block mb-2">' + (s.local.nombre_club || 'Sin club') + '</small>' +
                            '<div class="d-flex justify-content-center gap-1">' +
                                '<span class="badge bg-success">' + parseInt(s.local.victorias) + 'V</span>' +
                                '<span class="badge bg-danger">' + parseInt(s.local.derrotas) + 'D</span>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-2 text-center">' +
                            '<div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm suggestion-vs">' +
                                '<span class="fw-bold text-danger fs-5">VS</span>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-5 text-center">' + fotoVisitante +
                            '<h6 class="fw-bold mb-0">' + s.visitante.pnombre + ' ' + s.visitante.papellido + '</h6>' +
                            '<small class="text-muted d-block mb-2">' + (s.visitante.nombre_club || 'Sin club') + '</small>' +
                            '<div class="d-flex justify-content-center gap-1">' +
                                '<span class="badge bg-success">' + parseInt(s.visitante.victorias) + 'V</span>' +
                                '<span class="badge bg-danger">' + parseInt(s.visitante.derrotas) + 'D</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="text-center mt-4 pt-3 border-top">' +
                        '<a class="btn btn-primary rounded-pill px-4" href="?c=combates&a=form&local=' + s.local.id_atleta + '&visitante=' + s.visitante.id_atleta + '">' +
                            '<i class="bi bi-check-circle"></i> Crear Combate' +
                        '</a>' +
                    '</div>' +
                '</div></div></div>';
        });

        html += '</div>';
        $container.html(html);
    }

})(jQuery);
