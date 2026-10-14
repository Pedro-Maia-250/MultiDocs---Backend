<?php

require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Controllers/DocumentoController.php';
require_once __DIR__ . '/../src/Controllers/PdfController.php';

$page = $_GET['page'] ?? 'login';

$authController = new AuthController();
$documentoController = new DocumentoController();
$pdfController = new PdfController();

switch ($page) {

    case 'cadastro':
        $authController->cadastro();
        break;

    case 'login':
        $authController->login();
        break;

    case 'dashboard':
        include __DIR__ . '/../src/Views/dashboard/index.php';
        break;

    case 'documentos':
        $documentoController->index();
        break;

    case 'visualizar-documento':
        $documentoController->visualizar();
        break;

    case 'enviar-documento':
        $documentoController->enviar();
        break;

    case 'cadastrar-tipo':
        $documentoController->cadastrarTipo();
        break;

    // Exibe a tela e processa a solicitação de mesclagem.
    case 'mesclar-pdf':
        $pdfController->mesclar();
        break;

    // Abre o PDF mesclado no navegador.
    case 'visualizar-pdf-mesclado':
        $pdfController->visualizarMesclado();
        break;

    // Faz o download do PDF mesclado.
    case 'baixar-pdf-mesclado':
        $pdfController->baixarMesclado();
        break;

    default:
        $authController->login();
        break;
}