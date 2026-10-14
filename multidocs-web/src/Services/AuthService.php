<?php

require_once __DIR__ . '/../Core/ApiClient.php';

class AuthService
{
    private ApiClient $api;

    public function __construct()
    {
        $this->api = new ApiClient();
    }

    public function cadastrar(string $username, string $password)
    {
        return $this->api->post('/usuarios', [
            'username' => $username,
            'password' => $password
        ]);
    }

    public function login(string $username, string $password)
    {
        return $this->api->post('/auth', [
            'username' => $username,
            'password' => $password
        ]);
    }
}