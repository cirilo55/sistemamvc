<?php

namespace App\Controller;

use App\Service\AuthService;

class AuthController
{
    public function __construct(private AuthService $auth)
    {
    }

    public function showLogin(): void
    {
        include BASE_PATH . '/sys/login.php';
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->auth->attempt($username, $password);

        if (!$user) {
            header('Location: /login?error=invalid_credentials');
            exit();
        }

        session_regenerate_id(true);

        $_SESSION['id'] = $user->id;
        $_SESSION['userName'] = $user->userName;
        $_SESSION['lastName'] = $user->lastName;
        $_SESSION['imagePath'] = $user->imagePath;
        $_SESSION['profile'] = $user->imagePath;

        header('Location: /');
        exit();
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        header('Location: /login');
        exit();
    }
}
