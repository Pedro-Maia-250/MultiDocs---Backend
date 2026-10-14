<?php

require_once __DIR__ . '/../Core/ApiClient.php';

class PdfService
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    /**
     * Solicita à API Java a mesclagem de documentos PDF.
     *
     * @param array $documentos Lista de documentos que serão mesclados.
     * @param string $token Token de autenticação do usuário.
     *
     * @return array Resposta da API contendo o PDF ou informações de erro.
     */
    public function mesclar(
        array $documentos,
        string $token
    ): array {
        // Impede requisições sem documentos.
        if (empty($documentos)) {
            return [
                'status' => 0,
                'curl_error' => 'Selecione pelo menos um documento para mesclar.',
                'contentType' => null,
                'body' => null
            ];
        }

        // Encaminha a solicitação para a API Java.
        return $this->apiClient->postBinary(
            '/pdf/mesclar',
            $token,
            $documentos
        );
    }
}