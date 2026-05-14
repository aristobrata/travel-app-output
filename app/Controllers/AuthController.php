<?php
namespace App\Controllers;

use App\Core\{Request, Response};
use App\Middleware\CsrfMiddleware;
use App\Models\User;

class AuthController
{
    public function loginForm(): void
    {
        if (!empty($_SESSION['user_id'])) Response::redirect('/');
        require BASE_PATH . '/views/auth/login.php';
    }

    public function login(): void
    {
        $errors = Request::validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = $_POST;
            Response::redirect('/login');
        }

        $user = User::verify(Request::post('email', ''), Request::post('password', ''));

        if (!$user) {
            $_SESSION['error'] = 'Email atau password salah.';
            Response::redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        unset($_SESSION['errors'], $_SESSION['old']);

        $intended = $_SESSION['intended'] ?? ($user['role'] === 'admin' ? '/admin' : '/');
        unset($_SESSION['intended']);
        Response::redirect($intended);
    }

    public function registerForm(): void
    {
        if (!empty($_SESSION['user_id'])) Response::redirect('/');
        require BASE_PATH . '/views/auth/register.php';
    }

    public function register(): void
    {
        $errors = Request::validate([
            'name'     => 'required|min:2|max:100',
            'email'    => 'required|email',
            'phone'    => 'required',
            'password' => 'required|min:8',
        ]);

        if (!$errors && User::findByEmail(Request::post('email', ''))) {
            $errors['email'] = 'Email sudah terdaftar.';
        }

        if (!$errors && Request::post('password') !== Request::post('password_confirmation')) {
            $errors['password'] = 'Konfirmasi password tidak cocok.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = $_POST;
            Response::redirect('/register');
        }

        User::create([
            'name'     => Request::sanitize('name'),
            'email'    => Request::sanitize('email'),
            'phone'    => Request::sanitize('phone'),
            'password' => Request::post('password', ''),
        ]);

        $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
        Response::redirect('/login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        // Hapus cookie session agar browser tidak mengirim session ID lama
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        Response::redirect('/login');
    }
}
