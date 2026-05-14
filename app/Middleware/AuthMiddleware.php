<?php
namespace App\Middleware;
use App\Core\Response;

class AuthMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user_id'])) {
            // Jika request AJAX (fetch/XHR), kembalikan JSON 401 bukan redirect HTML
            $isAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
            if ($isAjax) {
                Response::json(['success' => false, 'message' => 'Sesi habis, silakan login kembali.', 'redirect' => '/login'], 401);
            }
            $_SESSION['intended'] = $_SERVER['REQUEST_URI'];
            Response::redirect('/login');
        }
    }
}
