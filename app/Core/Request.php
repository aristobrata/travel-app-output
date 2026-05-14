<?php
namespace App\Core;

class Request
{
    public static function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public static function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public static function sanitize(string $key): string
    {
        $val = self::input($key, '');
        return htmlspecialchars(trim((string)$val), ENT_QUOTES, 'UTF-8');
    }

    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    public static function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function json(): array
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? [];
    }

    public static function validate(array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = self::input($field, '');
            foreach (explode('|', $rule) as $r) {
                if ($r === 'required' && trim((string)$value) === '') {
                    $errors[$field] = ucfirst($field) . ' wajib diisi';
                } elseif (str_starts_with($r, 'min:')) {
                    $min = (int)substr($r, 4);
                    if (strlen((string)$value) < $min)
                        $errors[$field] = ucfirst($field) . " minimal $min karakter";
                } elseif (str_starts_with($r, 'max:')) {
                    $max = (int)substr($r, 4);
                    if (strlen((string)$value) > $max)
                        $errors[$field] = ucfirst($field) . " maksimal $max karakter";
                } elseif ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Format email tidak valid';
                } elseif ($r === 'numeric' && !is_numeric($value)) {
                    $errors[$field] = ucfirst($field) . ' harus berupa angka';
                }
            }
        }
        return $errors;
    }
}
