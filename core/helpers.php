<?php
/**
 * Helpers para formularios: preservar datos y mostrar errores
 */

if (!function_exists('old')) {
    function old(string $campo, $default = '') {
        $valor = isset($_POST[$campo]) ? $_POST[$campo] : $default;
        
        if (is_array($valor)) {
            return $valor;
        }
        
        return htmlspecialchars((string)$valor);
    }
}

if (!function_exists('hasError')) {
    function hasError(string $campo, array $errores = []): bool {
        return !empty($errores[$campo]);
    }
}

if (!function_exists('errorMsg')) {
    function errorMsg(string $campo, array $errores = []): string {
        return $errores[$campo] ?? '';
    }
}

if (!function_exists('fieldClass')) {
    function fieldClass(string $campo, array $errores = [], string $extra = ''): string {
        $clases = ['form-control'];
        if (!empty($extra)) $clases[] = $extra;
        if (hasError($campo, $errores)) {
            $clases[] = 'is-invalid';
        } elseif (isset($_POST[$campo]) && trim((string)$_POST[$campo]) !== '') {
            $clases[] = 'is-valid';
        }
        return implode(' ', $clases);
    }
}

if (!function_exists('renderErroresGenerales')) {
    function renderErroresGenerales(array $errores): string {
        if (empty($errores)) return '';
        $html = '<div class="alert alert-danger alert-errors alert-dismissible fade show">';
        $html .= '<strong><i class="bi bi-exclamation-triangle-fill"></i> Por favor corrige los siguientes errores:</strong>';
        $html .= '<ul>';
        foreach ($errores as $msg) {
            $html .= '<li>' . htmlspecialchars($msg) . '</li>';
        }
        $html .= '</ul>';
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        $html .= '</div>';
        return $html;
    }
}
if (!function_exists('fieldClassSelect')) {
    function fieldClassSelect(string $campo, array $errores = [], string $extra = ''): string {
        $clases = ['form-select'];
        if (!empty($extra)) $clases[] = $extra;
        if (hasError($campo, $errores)) {
            $clases[] = 'is-invalid';
        } elseif (isset($_POST[$campo]) && trim((string)$_POST[$campo]) !== '') {
            $clases[] = 'is-valid';
        }
        return implode(' ', $clases);
    }
}