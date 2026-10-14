<?php

require_once __DIR__ . '/../Services/DocumentoService.php';

class DocumentoController
{
    /**
     * Inicia a sessão se necessário.
     */
    private function iniciarSessao(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Lista os documentos do usuário.
     */
    public function index()
    {
        $this->iniciarSessao();

        if (!isset($_SESSION['token'])) {
            header('Location: ?page=login');
            exit;
        }

        $service = new DocumentoService();

        $resultado = $service->listar($_SESSION['token']);

        $documentos = $resultado['body'] ?? [];

        ob_start();

        include __DIR__ . '/../Views/documentos/index.php';

        $content = ob_get_clean();

        include __DIR__ . '/../Views/layouts/app.php';
    }

    /**
     * Exibe um documento PDF.
     */
    public function visualizar()
    {
        $this->iniciarSessao();

        if (!isset($_SESSION['token'])) {
            header('Location: ?page=login');
            exit;
        }

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            http_response_code(400);
            exit('Identificador de documento inválido.');
        }

        $service = new DocumentoService();

        $resultado = $service->visualizar(
            $id,
            $_SESSION['token']
        );

        if (
            ($resultado['status'] ?? 0) !== 200 ||
            empty($resultado['body'])
        ) {
            http_response_code(404);
            exit('Documento não encontrado.');
        }

        $contentType = $resultado['contentType'] ?? '';

        if (stripos($contentType, 'application/pdf') !== 0) {
            http_response_code(502);
            exit('A API não retornou um arquivo PDF válido.');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="documento.pdf"');

        echo $resultado['body'];

        exit;
    }

    /**
 * Exibe o formulário e processa o envio de documentos.
 */
public function enviar()
{
    $this->iniciarSessao();

    if (!isset($_SESSION['token'])) {
        header('Location: ?page=login');
        exit;
    }

    $mensagem = '';
    $tipoMensagem = '';

    $service = new DocumentoService();

    /*
     * Processa o formulário quando ele é enviado.
     */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $tipoId = filter_input(
            INPUT_POST,
            'tipoId',
            FILTER_VALIDATE_INT
        );

        if (!$tipoId || $tipoId < 1) {

            $mensagem = 'Selecione um tipo de documento válido.';
            $tipoMensagem = 'erro';

        } elseif (
            !isset($_FILES['arquivo']) ||
            $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK
        ) {

            $mensagem = 'Selecione um arquivo PDF válido.';
            $tipoMensagem = 'erro';

        } else {

            $arquivo = $_FILES['arquivo'];

            $extensao = strtolower(
                pathinfo($arquivo['name'], PATHINFO_EXTENSION)
            );

            if ($extensao !== 'pdf') {

                $mensagem = 'O arquivo precisa ter a extensão PDF.';
                $tipoMensagem = 'erro';

            } elseif (
                !is_uploaded_file($arquivo['tmp_name']) ||
                $arquivo['size'] <= 0
            ) {

                $mensagem = 'O arquivo enviado é inválido.';
                $tipoMensagem = 'erro';

            } else {

                /*
                 * Busca os tipos cadastrados na API.
                 */
                $resultadoTipos = $service->listar(
                    $_SESSION['token']
                );

                $tiposDisponiveis = [];

                if (
                    ($resultadoTipos['status'] ?? 0) === 200 &&
                    is_array($resultadoTipos['body'] ?? null)
                ) {
                    foreach ($resultadoTipos['body'] as $item) {

                        if (
                            !isset($item['tipo']) ||
                            !is_array($item['tipo'])
                        ) {
                            continue;
                        }

                        $tipo = $item['tipo'];

                        if (
                            isset($tipo['id'], $tipo['nome']) &&
                            is_numeric($tipo['id'])
                        ) {
                            $tiposDisponiveis[] = [
                                'id' => (int) $tipo['id'],
                                'nome' => $tipo['nome'],
                                'tipo' => $tipo['tipo'] ?? 'PADRAO'
                            ];
                        }
                    }
                }

                /*
                 * Verifica se o tipo selecionado existe na API.
                 */
                $tipoSelecionado = null;

                foreach ($tiposDisponiveis as $tipoDocumento) {

                    if ($tipoDocumento['id'] === (int) $tipoId) {
                        $tipoSelecionado = $tipoDocumento;
                        break;
                    }
                }

                if ($tipoSelecionado === null) {

                    $mensagem = 'O tipo selecionado não foi encontrado na API.';
                    $tipoMensagem = 'erro';

                } else {

                    /*
                     * Prepara os dados para o backend Java.
                     */
                    $dados = [
                        'tipo' => $tipoSelecionado['tipo'],
                        'tipoId' => $tipoSelecionado['id']
                    ];

                    /*
                     * Envia o documento para a API.
                     */
                    $resultado = $service->enviar(
                        $_SESSION['token'],
                        $dados,
                        $arquivo['tmp_name']
                    );

                    $status = $resultado['status'] ?? 0;

                    if ($status === 201) {

                        $mensagem = 'Documento enviado com sucesso!';
                        $tipoMensagem = 'sucesso';

                    } elseif ($status === 400) {

                        $mensagem = 'Erro 400 da API: ' .
                            ($resultado['raw_response'] ?? 'Sem detalhes');

                        $tipoMensagem = 'erro';

                    } elseif ($status === 401 || $status === 403) {

                        $mensagem = 'A API recusou a autenticação. Faça login novamente.';
                        $tipoMensagem = 'erro';

                    } elseif ($status === 404) {

                        $mensagem = 'O tipo de documento selecionado não foi encontrado na API.';
                        $tipoMensagem = 'erro';

                    } else {

                        $detalhes = $resultado['raw_response'] ?? '';

                        $mensagem = 'Não foi possível enviar o documento.';

                        if ($detalhes !== '') {
                            $mensagem .= ' Resposta da API: ' . $detalhes;
                        }

                        $tipoMensagem = 'erro';
                    }
                }
            }
        }
    }

    /*
     * Busca os tipos para preencher o select da tela.
     */
    $resultadoTipos = $service->listar(
        $_SESSION['token']
    );

    $tiposDocumentos = [];

    if (
        ($resultadoTipos['status'] ?? 0) === 200 &&
        is_array($resultadoTipos['body'] ?? null)
    ) {
        foreach ($resultadoTipos['body'] as $item) {

            if (
                !isset($item['tipo']) ||
                !is_array($item['tipo'])
            ) {
                continue;
            }

            $tipo = $item['tipo'];

            if (
                isset($tipo['id'], $tipo['nome']) &&
                is_numeric($tipo['id'])
            ) {
                $tiposDocumentos[] = [
                    'id' => (int) $tipo['id'],
                    'nome' => $tipo['nome'],
                    'tipo' => $tipo['tipo'] ?? 'PADRAO'
                ];
            }
        }
    }

    /*
     * Monta a página.
     */
    ob_start();

    include __DIR__ . '/../Views/documentos/enviar.php';

    $content = ob_get_clean();

    include __DIR__ . '/../Views/layouts/app.php';
}

    /**
 * Exibe o formulário e processa o cadastro de tipos de documentos.
 */
public function cadastrarTipo()
{
    $this->iniciarSessao();

    if (!isset($_SESSION['token'])) {
        header('Location: ?page=login');
        exit;
    }

    $mensagem = '';
    $tipoMensagem = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');

        if ($nome === '') {

            $mensagem = 'Informe o nome do tipo de documento.';
            $tipoMensagem = 'erro';

        } else {

            $service = new DocumentoService();

            $resultado = $service->cadastrarTipo(
                $_SESSION['token'],
                $nome
            );

            $status = $resultado['status'] ?? 0;

            if ($status === 201) {

                $mensagem = 'Tipo de documento cadastrado com sucesso!';
                $tipoMensagem = 'sucesso';

            } elseif ($status === 409) {

                $mensagem = 'Esse tipo de documento já está cadastrado.';
                $tipoMensagem = 'erro';

            } elseif ($status === 400) {

                $mensagem = 'Os dados enviados são inválidos.';
                $tipoMensagem = 'erro';

            } else {

                $mensagem = 'Não foi possível cadastrar o tipo de documento. Verifique a conexão com a API e tente novamente.';
                $tipoMensagem = 'erro';
            }
        }
    }

    ob_start();

    include __DIR__ . '/../Views/documentos/cadastrar-tipo.php';

    $content = ob_get_clean();

    include __DIR__ . '/../Views/layouts/app.php';
}



}