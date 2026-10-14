<?php

require_once __DIR__ . '/../Services/AuthService.php';

class AuthController
{
    public function cadastro()
    {
        $mensagem = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $service = new AuthService();

            $resultado = $service->cadastrar(
                $_POST['username'],
                $_POST['password']
            );

            if ($resultado['status'] === 201) {

                $mensagem = 'Usuário criado com sucesso!';

            } elseif ($resultado['status'] === 409) {

                $mensagem = 'Usuário já existe.';

            } else {

                $mensagem = 'Erro ao cadastrar usuário.';
            }
        }

        $title = 'Cadastro';

        $view = __DIR__ . '/../Views/auth/cadastro.php';

        include __DIR__ . '/../Views/layouts/auth.php';
    }

    public function login()
    {
        $mensagem = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $service = new AuthService();

            $resultado = $service->login(
                $_POST['username'],
                $_POST['password']
            );

            if ($resultado['status'] === 200) {

                session_start();

                $_SESSION['token'] =
                    $resultado['body']['token'];

                $_SESSION['token_type'] =
                    $resultado['body']['type'];

                $_SESSION['expires_in'] =
                    $resultado['body']['expiresIn'];

                header('Location: ?page=dashboard');
                exit;
            }

            $mensagem = 'Usuário ou senha inválidos.';
        }

        $title = 'Login';

        $view = __DIR__ . '/../Views/auth/login.php';

        include __DIR__ . '/../Views/layouts/auth.php';
    }
}