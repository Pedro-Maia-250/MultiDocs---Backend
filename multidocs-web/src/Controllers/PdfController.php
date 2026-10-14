<?php

require_once __DIR__ . '/../Services/PdfService.php';
require_once __DIR__ . '/../Services/DocumentoService.php';

class PdfController
{
    private PdfService $pdfService;
    private DocumentoService $documentoService;

    public function __construct()
    {
        $this->pdfService = new PdfService();
        $this->documentoService = new DocumentoService();
    }

    /**
     * Exibe a página de mesclagem de PDFs.
     */
    public function mesclar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se o usuário está autenticado.
        if (empty($_SESSION['token'])) {
            header('Location: ?page=login');
            exit;
        }

        $token = $_SESSION['token'];

        // Busca os documentos disponíveis na API.
        $resultado = $this->documentoService->listar($token);

        $documentos = [];
        $erro = null;
        $sucesso = null;

        if (
            isset($resultado['status']) &&
            $resultado['status'] >= 200 &&
            $resultado['status'] < 300 &&
            is_array($resultado['body'])
        ) {
            $documentos = $resultado['body'];
        } else {
            $erro = 'Não foi possível carregar os documentos disponíveis.';
        }

        // Processa o formulário de mesclagem.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idsSelecionados = $_POST['documentos'] ?? [];

            if (!is_array($idsSelecionados) || empty($idsSelecionados)) {
                $erro = 'Selecione pelo menos um documento para mesclar.';
            } else {
                /*
                 * Localiza os documentos selecionados na listagem.
                 *
                 * IMPORTANTE:
                 * O identificador enviado à API ainda precisa ser
                 * confirmado com o contrato do endpoint de mesclagem.
                 */
                $documentosSelecionados = [];

                foreach ($idsSelecionados as $idSelecionado) {
                    foreach ($documentos as $item) {
                        if (
                            isset($item['tipo']['id']) &&
                            (string) $item['tipo']['id'] === (string) $idSelecionado
                        ) {
                            $documentosSelecionados[] = [
                                'tipo' => $item['tipo']['tipo'] ?? 'PADRAO',
                                'tipoId' => $item['tipo']['id']
                            ];

                            break;
                        }
                    }
                }

                if (count($documentosSelecionados) !== count($idsSelecionados)) {
                    $erro = 'Um ou mais documentos selecionados são inválidos.';
                } else {
                    $resultadoPdf = $this->pdfService->mesclar(
                        $documentosSelecionados,
                        $token
                    );

                    $status = $resultadoPdf['status'] ?? 0;
                    $contentType = $resultadoPdf['contentType'] ?? '';
                    $conteudoPdf = $resultadoPdf['body'] ?? null;

                    if (
                        $status >= 200 &&
                        $status < 300 &&
                        is_string($conteudoPdf) &&
                        str_starts_with($conteudoPdf, '%PDF-')
                    ) {
                        // Armazena o PDF na sessão para a rota de visualização.
                        $_SESSION['pdf_mesclado'] = $conteudoPdf;

                        header('Location: ?page=mesclar-pdf&resultado=sucesso');
                        exit;
                    }

                    if ($status === 404) {
                        $erro = 'Um ou mais documentos não foram encontrados pela API.';
                    } elseif ($status === 400) {
                        $erro = 'A API não aceitou os dados enviados para a mesclagem.';
                    } elseif ($status === 401 || $status === 403) {
                        $erro = 'A autenticação falhou ou você não tem permissão para realizar essa operação.';
                    } elseif ($status === 0) {
                        $erro = 'Não foi possível estabelecer comunicação com a API.';
                    } else {
                        $erro = 'Não foi possível mesclar os documentos. Código HTTP: ' . $status;
                    }
                }
            }
        }

        // Disponibiliza as variáveis para a interface.
        require __DIR__ . '/../Views/pdf/mesclar.php';
    }

    /**
     * Exibe o PDF resultante da mesclagem.
     */
    public function visualizarMesclado()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['token'])) {
            header('Location: ?page=login');
            exit;
        }

        $pdf = $_SESSION['pdf_mesclado'] ?? null;

        if (
            !is_string($pdf) ||
            !str_starts_with($pdf, '%PDF-')
        ) {
            http_response_code(404);
            echo 'Nenhum PDF mesclado disponível.';
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="documentos-mesclados.pdf"');
        header('Content-Length: ' . strlen($pdf));
        header('Cache-Control: private, no-store');

        echo $pdf;
        exit;
    }

    /**
     * Permite baixar o PDF resultante.
     */
    public function baixarMesclado()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['token'])) {
            header('Location: ?page=login');
            exit;
        }

        $pdf = $_SESSION['pdf_mesclado'] ?? null;

        if (
            !is_string($pdf) ||
            !str_starts_with($pdf, '%PDF-')
        ) {
            http_response_code(404);
            echo 'Nenhum PDF mesclado disponível.';
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="documentos-mesclados.pdf"');
        header('Content-Length: ' . strlen($pdf));
        header('Cache-Control: private, no-store');

        echo $pdf;
        exit;
    }
}