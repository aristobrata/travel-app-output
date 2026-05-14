<?php
namespace App\Middleware;
use App\Core\Response;

class AdminMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['intended'] = $_SERVER['REQUEST_URI'];
            Response::redirect('/login');
        }
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            require BASE_PATH . '/views/errors/403.php';
            exit;
        }
    }
}
