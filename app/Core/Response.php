<?php
namespace App\Core;

class Response
{
    public static function redirect(string $path): void
    {
        $base = defined('SUBFOLDER') ? SUBFOLDER : '';
        $url  = $base . '/' . ltrim($path, '/');
        if (ob_get_level() > 0) ob_end_clean();
        header('Location: ' . $url);
        exit;
    }

    public static function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? (defined('SUBFOLDER') ? SUBFOLDER . '/' : '/');
        if (ob_get_level() > 0) ob_end_clean();
        header('Location: ' . $ref);
        exit;
    }

    public static function json(mixed $data, int $code = 200): void
    {
        // Bersihkan buffer agar notice/warning tidak merusak JSON
        if (ob_get_level() > 0) ob_clean();
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
