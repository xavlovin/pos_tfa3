<?php

/**
 * Small view helpers shared by every page (loaded in BaseController).
 */

if (! function_exists('icon')) {
    /**
     * Renders an SVG icon from the inline sprite in partials/sprite.php.
     */
    function icon(string $name, string $class = ''): string
    {
        $class = trim('icon ' . $class);

        return '<svg class="' . esc($class, 'attr') . '" aria-hidden="true" focusable="false">'
            . '<use href="#i-' . esc($name, 'attr') . '"></use></svg>';
    }
}

if (! function_exists('field_error')) {
    /**
     * The validation error for one field (after a redirect()->withInput()), or null.
     */
    function field_error(string $field): ?string
    {
        $errors = validation_errors();

        return $errors[$field] ?? null;
    }
}

if (! function_exists('invalid')) {
    /**
     * Returns the CSS class that marks a field as invalid.
     */
    function invalid(string $field): string
    {
        return field_error($field) !== null ? ' is-invalid' : '';
    }
}

if (! function_exists('error_text')) {
    /**
     * Renders the inline error message under a field, if there is one.
     */
    function error_text(string $field): string
    {
        $error = field_error($field);

        if ($error === null) {
            return '';
        }

        return '<p class="field-error" id="' . esc($field, 'attr') . '-error" role="alert">'
            . icon('alert', 'icon-sm') . '<span>' . esc($error) . '</span></p>';
    }
}

if (! function_exists('avatar_url')) {
    /**
     * Public URL of a user's prepared avatar, or the placeholder image.
     *
     * @param string|null $filename The filename stored in users.avatar
     * @param string      $size     'thumb' (listing) or 'display' (edit page)
     */
    function avatar_url(?string $filename, string $size = 'thumb'): string
    {
        if ($filename !== null && $filename !== '') {
            $filename = basename($filename);
            $relative = 'uploads/avatars/' . ($size === 'thumb' ? 'thumbs/' : '') . $filename;

            if (is_file(FCPATH . $relative)) {
                return base_url($relative);
            }
        }

        return base_url('assets/img/avatar-placeholder.svg');
    }
}

if (! function_exists('initials')) {
    function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $last  = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';

        return mb_strtoupper($first . $last);
    }
}

if (! function_exists('nice_date')) {
    function nice_date(?string $datetime): string
    {
        if ($datetime === null || $datetime === '') {
            return '—';
        }

        return date('M j, Y', strtotime($datetime));
    }
}
