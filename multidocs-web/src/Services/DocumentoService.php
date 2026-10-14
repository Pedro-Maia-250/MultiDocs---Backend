<?php

require_once __DIR__ . '/../Core/ApiClient.php';

class DocumentoService
{
    private ApiClient $api;

    public function __construct()
    {
        $this->api = new ApiClient();
    }

    /**
     * Lista os documentos cadastrados na API.
     */
    public function listar(string $token)
    {
        return $this->api->get(
            '/documentos',
            $token
        );
    }

    /**
     * Obtém o PDF de um documento.
     */
    public function visualizar(int $id, string $token)
    {
        return $this->api->getBinary(
            "/documentos/$id",
            $token
        );
    }

    /**
     * Envia um documento PDF para a API.
     *
     * @param string $token Token de autenticação.
     * @param array $dados Dados do tipo de documento.
     * @param string $caminhoArquivo Caminho temporário do PDF.
     */
    public function enviar(
        string $token,
        array $dados,
        string $caminhoArquivo
    ) {
        return $this->api->uploadFile(
            '/documentos',
            $token,
            $dados,
            $caminhoArquivo
        );
    }

    /**
     * Cadastra um novo tipo de documento na API.
     *
     * @param string $token Token de autenticação.
     * @param string $nome Nome do tipo de documento.
     */
    public function cadastrarTipo(
        string $token,
        string $nome
    ) {
        return $this->api->post(
            '/documentos/tipos',
            [
                'nome' => $nome
            ],
            $token
        );
    }
}