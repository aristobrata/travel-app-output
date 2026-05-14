<?php
namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $token = $_POST['_csrf'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
                http_response_code(403);
                die('<h2>403 - CSRF token tidak valid.</h2><a href="javascript:history.back()">Kembali</a>');
            }
        }
    }

    public static function token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }
}
