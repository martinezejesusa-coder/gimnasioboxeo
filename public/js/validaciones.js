/* ============================================================
   SISTEMA DE VALIDACIÓN EN TIEMPO REAL + AJAX (jQuery)
   ============================================================ */
(function ($) {
    'use strict';

    $(document).ready(function () {
        $('form[data-validate]').each(function () {
            initForm($(this));
        });
    });

    function initForm($form) {
        const $fields = $form.find(
            'input:not([type=hidden]):not([type=submit]):not([type=button]):not([type=reset]):not([type=file]), select, textarea'
        );
        const $submitBtn = $form.find('button[type="submit"]');

        if ($submitBtn.length) $submitBtn.prop('disabled', true);

        $fields.each(function () {
            const $field = $(this);
            $field.data('touched', false);
            if ($field.hasClass('is-invalid')) $field.data('touched', true);

            $field.on('input', function () {
                $field.data('touched', true);
                validateField($field);
                checkFormValidity($form, $fields, $submitBtn);
                actualizarContador($field);
            });

            $field.on('blur', function () {
                $field.data('touched', true);
                validateField($field);
                checkFormValidity($form, $fields, $submitBtn);
                actualizarContador($field);
            });

            $field.on('change', function () {
                if ($field.attr('type') === 'checkbox' || 
                    $field.prop('tagName') === 'SELECT' || 
                    $field.attr('type') === 'date') {
                    $field.data('touched', true);
                    validateField($field);
                }
                checkFormValidity($form, $fields, $submitBtn);
            });

            actualizarContador($field);
        });

        $form.on('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let allValid = true;
            let $firstError = null;

            $fields.each(function () {
                const $field = $(this);
                $field.data('touched', true);
                if (!validateField($field)) {
                    allValid = false;
                    if (!$firstError) $firstError = $field;
                }
            });

            if (!allValid) {
                scrollToError($firstError);
                if (navigator.vibrate) navigator.vibrate([80, 40, 80]);
                if (typeof mostrarNotificacion === 'function') {
                    mostrarNotificacion('Por favor, corrige los errores del formulario.', 'danger', 'Error de Validación');
                }
                return false;
            }

            if ($form.is('[data-ajax]')) {
                enviarAjax($form, $fields, $submitBtn);
            } else {
                $form.off('submit').submit();
            }
        });

        checkFormValidity($form, $fields, $submitBtn);
    }

    function enviarAjax($form, $fields, $submitBtn) {
        const originalHtml = $submitBtn.length ? $submitBtn.html() : '';

        if ($submitBtn.length) {
            $submitBtn.prop('disabled', true)
                      .html('<span class="spinner-border spinner-border-sm me-2"></span> Guardando...');
        }

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: new FormData($form[0]),
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                console.log('📥 Respuesta del servidor:', response);

                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }

                if (response.status === 'success') {
                    if (typeof mostrarNotificacion === 'function') {
                        mostrarNotificacion(response.mensaje || 'Operación exitosa', 'success', '¡Éxito!');
                    }
                    if (response.url_redirect) {
                        setTimeout(function () {
                            window.location.href = response.url_redirect;
                        }, 800);
                    }
                } else {
                    // SI HAY ERRORES ESPECÍFICOS, SOLO MOSTRAR ESOS
                    if (response.errores && Object.keys(response.errores).length > 0) {
                        marcarErrores($form, response.errores);
                    } else {
                        // Solo si no hay errores específicos, mostrar el genérico
                        if (typeof mostrarNotificacion === 'function') {
                            mostrarNotificacion(response.mensaje || 'Error al procesar el formulario', 'danger', 'Error');
                        }
                    }
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.status === 'error') {
                    if (xhr.responseJSON.errores && Object.keys(xhr.responseJSON.errores).length > 0) {
                        marcarErrores($form, xhr.responseJSON.errores);
                    } else {
                        if (typeof mostrarNotificacion === 'function') {
                            mostrarNotificacion(
                                xhr.responseJSON.mensaje || 'Error al procesar el formulario', 
                                'danger', 
                                'Error'
                            );
                        }
                    }
                } else {
                    let mensaje = 'Error de conexión con el servidor';
                    if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                        mensaje = xhr.responseJSON.mensaje;
                    }
                    if (typeof mostrarNotificacion === 'function') {
                        mostrarNotificacion(mensaje, 'danger', 'Error');
                    }
                }
            },
            complete: function () {
                if ($submitBtn.length) {
                    $submitBtn.prop('disabled', false).html(originalHtml);
                    checkFormValidity($form, $fields, $submitBtn);
                }
            }
        });
    }

    function marcarErrores($form, errores) {
        $form.find('.is-invalid').removeClass('is-invalid');
        let erroresNoMapeados = [];

        $.each(errores, function (name, mensaje) {
            const $field = $form.find('[name="' + name + '"]');
            
            if (!$field.length) {
            
                console.log('⚠️ Error sin input mapeado:', name, '→', mensaje);
                erroresNoMapeados.push(mensaje);
                return;
            }

            $field.addClass('is-invalid');
            $field.data('touched', true);

            let $feedback = $field.parent().find('.invalid-feedback');
            if (!$feedback.length) {
                $feedback = $('<div class="invalid-feedback d-block"></div>').appendTo($field.parent());
            }
            $feedback.text(mensaje);
        });

        
        if (erroresNoMapeados.length > 0) {
            console.log('📢 Mostrando Toast con:', erroresNoMapeados.join(' | '));
            if (typeof mostrarNotificacion === 'function') {
                mostrarNotificacion(erroresNoMapeados.join(' | '), 'danger', 'Error de Validación');
            }
        }
    }

    function scrollToError($field) {
        if (!$field || !$field.length) return;
        $('html, body').animate({
            scrollTop: $field.offset().top - 120
        }, 350, function () {
            $field.focus();
        });
    }

    function checkFormValidity($form, $fields, $submitBtn) {
        if (!$submitBtn || !$submitBtn.length) return;
        let isValid = true;

        $fields.each(function () {
            const $field = $(this);
            if ($field.prop('disabled') || $field.prop('readOnly')) return;
            if (validateField($field, true) === false) isValid = false;
        });

        $submitBtn.prop('disabled', !isValid);
    }

    function validateField($field, silent) {
        silent = silent === true;
        if ($field.prop('disabled') || $field.prop('readOnly')) return true;

        let rawVal = $field.val();
        if (Array.isArray(rawVal)) rawVal = rawVal.join(',');
        const value = (rawVal != null ? String(rawVal) : '').trim();

        let error = '';

        if ($field.attr('required') && value === '' && !$field.is('[type="checkbox"]')) {
            error = $field.data('msgRequired') || 'Este campo es obligatorio';
        }
        else if ($field.attr('type') === 'checkbox' && $field.attr('required') && !$field.prop('checked')) {
            error = $field.data('msgRequired') || 'Debes marcar esta casilla';
        }
        else if (value && $field.attr('minlength') && value.length < parseInt($field.attr('minlength'), 10)) {
            error = $field.data('msgMinlength') || ('Debe tener al menos ' + $field.attr('minlength') + ' caracteres');
        }
        else if (value && $field.attr('maxlength') && value.length > parseInt($field.attr('maxlength'), 10)) {
            error = $field.data('msgMaxlength') || ('No puede superar ' + $field.attr('maxlength') + ' caracteres');
        }
        else if (value && $field.attr('pattern')) {
            try {
                const regex = new RegExp('^(?:' + $field.attr('pattern') + ')$', 'u');
                if (!regex.test(value)) error = $field.data('msgPattern') || 'El formato no es válido';
            } catch (err) { }
        }
        else if ($field.attr('type') === 'email' && value) {
            const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!regex.test(value)) error = $field.data('msgEmail') || 'Ingresa un correo válido';
        }
        else if ($field.attr('type') === 'tel' && value) {
            const regex = /^\+?[\d\s\-\(\)\.]{7,20}$/;
            if (!regex.test(value)) error = $field.data('msgTel') || 'Ingresa un teléfono válido';
        }
        else if ($field.attr('type') === 'number' && value !== '') {
            const regexNum = /^-?\d+(\.\d+)?$/;
            if (!regexNum.test(value)) {
                error = 'Debe ser un número válido';
            } else {
                const num = parseFloat(value);
                if ($field.attr('min') && num < parseFloat($field.attr('min'))) error = $field.data('msgMin') || ('El mínimo es ' + $field.attr('min'));
                else if ($field.attr('max') && num > parseFloat($field.attr('max'))) error = $field.data('msgMax') || ('El máximo es ' + $field.attr('max'));
            }
        }
        else if ($field.attr('type') === 'date' && value) {
            const regexFecha = /^\d{4}-\d{2}-\d{2}$/;
            if (!regexFecha.test(value)) {
                error = 'Formato de fecha inválido (use YYYY-MM-DD)';
            } else if ($field.attr('min') && value < $field.attr('min')) {
                error = 'La fecha no puede ser anterior a ' + $field.attr('min');
            } else if ($field.attr('max') && value > $field.attr('max')) {
                error = 'La fecha no puede ser posterior a ' + $field.attr('max');
            }
        }

        if (!error && value && $field.is('[data-differs-from]')) {
            const $otro = $('#' + $field.attr('data-differs-from'));
            if ($otro.length) {
                let otroVal = $otro.val();
                if (Array.isArray(otroVal)) otroVal = otroVal.join(',');
                otroVal = (otroVal != null ? String(otroVal) : '').trim();
                if (otroVal && otroVal === value) {
                    error = $field.data('msgDiffers') || 'Este valor no puede ser igual al otro campo';
                }
            }
        }

        if (!error && value && $field.is('[data-same-value-as]')) {
            const $otro = $('#' + $field.attr('data-same-value-as'));
            if ($otro.length) {
                let otroVal = $otro.val();
                if (Array.isArray(otroVal)) otroVal = otroVal.join(',');
                otroVal = (otroVal != null ? String(otroVal) : '').trim();
                if (otroVal !== value) {
                    error = $field.data('msgSameValueAs') || 'Los valores no coinciden';
                }
            }
        }

        if (!silent) setFieldState($field, error);
        return error === '';
    }

    function setFieldState($field, error) {
        const $feedback = getFeedbackElement($field);
        let val = $field.val();
        if (Array.isArray(val)) val = val.join(',');
        const isEmpty = !val || String(val).trim() === '';

        if (error) {
            $field.removeClass('is-valid').addClass('is-invalid');
            $feedback.attr('class', 'invalid-feedback d-block').text(error).attr('data-feedback', 'true');
            $field.attr('aria-invalid', 'true');
        } else if (!isEmpty && !$field.is('[data-no-success]')) {
            $field.removeClass('is-invalid').addClass('is-valid');
            $feedback.attr('class', 'valid-feedback d-block').text($field.data('msgValid') || '¡Correcto!').attr('data-feedback', 'true');
            $field.attr('aria-invalid', 'false');
        } else {
            $field.removeClass('is-valid is-invalid');
            $feedback.attr('class', 'form-text').text($field.data('hint') || '').attr('data-feedback', 'true');
            $field.removeAttr('aria-invalid');
        }
    }

    function getFeedbackElement($field) {
        let $parent = $field.parent();
        if ($parent.hasClass('input-group')) $parent = $parent.parent();

        let $feedback = $parent.find('[data-feedback="true"]');
        if (!$feedback.length) {
            const $phpRendered = $parent.find('.invalid-feedback, .valid-feedback');
            if ($phpRendered.length) {
                $phpRendered.attr('data-feedback', 'true');
                $feedback = $phpRendered;
            }
        }
        if (!$feedback.length) {
            $feedback = $('<div></div>').addClass('form-text').attr('data-feedback', 'true').appendTo($parent);
        }
        return $feedback;
    }

    function actualizarContador($field) {
        if (!$field.attr('maxlength')) return;
        let $contador = $field.parent().find('.char-counter');
        if (!$contador.length) {
            $contador = $('<small></small>').addClass('char-counter text-muted').css({
                'font-size': '0.72rem',
                'float': 'right'
            }).appendTo($field.parent());
        }
        let val = $field.val();
        if (Array.isArray(val)) val = val.join(',');
        const length = (val != null ? String(val) : '').length;
        $contador.text(length + '/' + $field.attr('maxlength'));
    }

    window.revalidarCampo = function (field) {
        if (!field) return;
        const $field = $(field);
        const $form = $field.closest('form[data-validate]');
        if (!$form.length) return;

        const $fields = $form.find(
            'input:not([type=hidden]):not([type=submit]):not([type=button]):not([type=reset]):not([type=file]), select, textarea'
        );
        const $submitBtn = $form.find('button[type="submit"]');

        validateField($field);
        checkFormValidity($form, $fields, $submitBtn);
    };

    // ============================================================
    // MANEJO DEL BOTÓN "LIMPIAR"
    // ============================================================
    $(document).on('click', 'form[data-validate] button[type="reset"]', function (e) {
        e.preventDefault();
        var $form = $(this).closest('form[data-validate]');
        if (!$form.length) return;

        $form.find('select').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).val(null).trigger('change.select2');
            }
        });

        $form[0].reset();
        $form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        $form.find('[data-feedback="true"]').each(function () {
            var $fb = $(this);
            var $field = $fb.siblings('input, select, textarea').first();
            $fb.attr('class', 'form-text').text($field.data('hint') || '');
        });
        $form.find('button[type="submit"]').prop('disabled', true);
    });

})(jQuery);