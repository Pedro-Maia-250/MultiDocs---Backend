<?php

class ApiClient
{
    private string $baseUrl;

    public function __construct()
    {
        $config = require __DIR__ . '/../Config/api.php';
        $this->baseUrl = rtrim($config['base_url'], '/');
    }

    /**
     * Realiza requisições POST com corpo JSON.
     * Utilizado no cadastro de usuários, login
     * e cadastro de tipos de documentos.
     */
    /**
 * Realiza requisições POST com corpo JSON.
 * Utilizado no cadastro de usuários, login
 * e cadastro de tipos de documentos.
 */
public function post(
    string $endpoint,
    array $data,
    ?string $token = null
) {
    $url = $this->baseUrl . $endpoint;

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json'
    ];

    // Adiciona autenticação quando um token é informado.
    if ($token !== null && $token !== '') {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    $json = json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
    );

    if ($json === false) {
        return [
            'url' => $url,
            'status' => 0,
            'curl_error' => 'Não foi possível converter os dados para JSON.',
            'raw_response' => null,
            'body' => null
        ];
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $response = curl_exec($curl);

    $statusCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $error = curl_error($curl);

    curl_close($curl);

    return [
        'url' => $url,
        'status' => $statusCode,
        'curl_error' => $error,
        'raw_response' => $response,
        'body' => is_string($response)
            ? json_decode($response, true)
            : null
    ];
}

    /**
     * Realiza requisições GET que retornam JSON.
     * Utilizado na listagem de documentos.
     */
    public function get(string $endpoint, string $token)
    {
        $url = $this->baseUrl . $endpoint;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . $token
            ]
        ]);

        $response = curl_exec($curl);

        $statusCode = curl_getinfo(
            $curl,
            CURLINFO_HTTP_CODE
        );

        $error = curl_error($curl);

        curl_close($curl);

        return [
            'url' => $url,
            'status' => $statusCode,
            'curl_error' => $error,
            'raw_response' => $response,
            'body' => is_string($response)
                ? json_decode($response, true)
                : null
        ];
    }

    /**
     * Realiza requisições GET que retornam arquivos binários.
     * Utilizado para visualizar PDFs.
     */
    public function getBinary(
        string $endpoint,
        string $token
    ) {
        $url = $this->baseUrl . $endpoint;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/pdf',
                'Authorization: Bearer ' . $token
            ]
        ]);

        $response = curl_exec($curl);

        $statusCode = curl_getinfo(
            $curl,
            CURLINFO_HTTP_CODE
        );

        $contentType = curl_getinfo(
            $curl,
            CURLINFO_CONTENT_TYPE
        );

        $error = curl_error($curl);

        curl_close($curl);

        return [
            'url' => $url,
            'status' => $statusCode,
            'contentType' => $contentType,
            'curl_error' => $error,
            'body' => $response
        ];
    }

/**
 * Realiza requisições POST com corpo JSON
 * que retornam arquivos binários, como PDFs.
 *
 * Utilizado para mesclar documentos PDF.
 */
public function postBinary(
    string $endpoint,
    string $token,
    array $data
) {
    $url = $this->baseUrl . $endpoint;

    // Converte os dados enviados para JSON.
    $json = json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
    );

    if ($json === false) {
        return [
            'url' => $url,
            'status' => 0,
            'contentType' => null,
            'curl_error' => 'Não foi possível converter os dados para JSON.',
            'body' => null
        ];
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json,

        CURLOPT_HTTPHEADER => [
            'Accept: application/pdf',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]
    ]);

    $response = curl_exec($curl);

    $statusCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $contentType = curl_getinfo(
        $curl,
        CURLINFO_CONTENT_TYPE
    );

    $error = curl_error($curl);

    curl_close($curl);

    return [
        'url' => $url,
        'status' => $statusCode,
        'contentType' => $contentType,
        'curl_error' => $error,
        'body' => is_string($response)
            ? $response
            : null
    ];
}



  /**
 * Envia um documento PDF para a API.
 */
public function uploadFile(
    string $endpoint,
    string $token,
    array $dados,
    string $caminhoArquivo
) {
    $url = $this->baseUrl . $endpoint;

    // Verifica se o arquivo existe e pode ser lido.
    if (!is_file($caminhoArquivo) || !is_readable($caminhoArquivo)) {
        return [
            'url' => $url,
            'status' => 0,
            'curl_error' => 'Arquivo não encontrado ou sem permissão de leitura.',
            'raw_response' => null,
            'body' => null
        ];
    }

    // Converte os dados do documento para JSON.
    $dadosJson = json_encode(
        $dados,
        JSON_UNESCAPED_UNICODE
    );

    if ($dadosJson === false) {
        return [
            'url' => $url,
            'status' => 0,
            'curl_error' => 'Não foi possível converter os dados para JSON.',
            'raw_response' => null,
            'body' => null
        ];
    }

    // Cria um arquivo temporário contendo o JSON.
    $arquivoDados = tempnam(sys_get_temp_dir(), 'multidocs_dados_');

    if ($arquivoDados === false) {
        return [
            'url' => $url,
            'status' => 0,
            'curl_error' => 'Não foi possível criar o arquivo temporário dos dados.',
            'raw_response' => null,
            'body' => null
        ];
    }

    if (file_put_contents($arquivoDados, $dadosJson) === false) {
        unlink($arquivoDados);

        return [
            'url' => $url,
            'status' => 0,
            'curl_error' => 'Não foi possível gravar os dados JSON.',
            'raw_response' => null,
            'body' => null
        ];
    }

    // Prepara a parte JSON da requisição.
    $parteDados = new CURLFile(
        $arquivoDados,
        'application/json',
        'dados.json'
    );

    // Prepara o PDF.
    $arquivoPdf = new CURLFile(
        $caminhoArquivo,
        'application/pdf',
        'documento.pdf'
    );

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS => [
            'dados' => $parteDados,
            'arquivo' => $arquivoPdf
        ],

        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ]
    ]);

    $response = curl_exec($curl);

    $statusCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $error = curl_error($curl);

    curl_close($curl);

    // Remove o arquivo temporário.
    if (is_file($arquivoDados)) {
        unlink($arquivoDados);
    }

    return [
        'url' => $url,
        'status' => $statusCode,
        'curl_error' => $error,
        'raw_response' => $response,
        'body' => is_string($response)
            ? json_decode($response, true)
            : null
    ];
}}