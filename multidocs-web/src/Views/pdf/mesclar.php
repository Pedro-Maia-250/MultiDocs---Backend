<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function escaparPdf($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$pdfDisponivel = !empty($_SESSION['pdf_mesclado']);

$mesclagemConcluida = (
    ($_GET['resultado'] ?? '') === 'sucesso'
    && $pdfDisponivel
);

ob_start();
?>

<div class="pdf-container">

    <h1>Mesclar PDFs</h1>

    <p>
        Selecione os documentos que deseja reunir em um único arquivo PDF.
    </p>

    <?php if (!empty($erro)): ?>
        <div class="mensagem erro">
            <?= escaparPdf($erro) ?>
        </div>
    <?php endif; ?>

    <?php if ($mesclagemConcluida): ?>
        <div class="mensagem sucesso">
            PDFs mesclados com sucesso!
        </div>

        <div class="acoes-pdf">
            <a
                href="?page=visualizar-pdf-mesclado"
                target="_blank"
                rel="noopener noreferrer"
            >
                Visualizar PDF
            </a>

            <a href="?page=baixar-pdf-mesclado">
                Baixar PDF
            </a>
        </div>
    <?php endif; ?>

    <form
        action="?page=mesclar-pdf"
        method="POST"
        id="formMesclarPdf"
    >

        <div class="selecao-documentos">

            <div class="cabecalho-documentos">
                <h2>Meus documentos</h2>

                <label>
                    <input
                        type="checkbox"
                        id="selecionarTodos"
                    >
                    Selecionar todos
                </label>
            </div>

            <?php
            $quantidadeDocumentos = 0;
            ?>

            <?php if (!empty($documentos) && is_array($documentos)): ?>

                <?php foreach ($documentos as $item): ?>

                    <?php
                    $tipo = $item['tipo'] ?? [];
                    $documento = $item['documento'] ?? null;

                    /*
                     * Exibimos somente os itens que possuem
                     * um documento associado.
                     */
                    if (
                        !is_array($documento)
                        || !isset($tipo['id'])
                    ) {
                        continue;
                    }

                    $tipoId = $tipo['id'];
                    $nomeTipo = $tipo['nome'] ?? 'Documento';
                    $dataDocumento = $documento['data'] ?? null;

                    $quantidadeDocumentos++;
                    ?>

                    <label class="documento-item">

                        <input
                            type="checkbox"
                            name="documentos[]"
                            value="<?= escaparPdf($tipoId) ?>"
                            class="documento-checkbox"
                        >

                        <span class="documento-informacoes">

                            <strong>
                                <?= escaparPdf($nomeTipo) ?>
                            </strong>

                            <?php if (!empty($dataDocumento)): ?>
                                <small>
                                    Data:
                                    <?= escaparPdf($dataDocumento) ?>
                                </small>
                            <?php endif; ?>

                        </span>

                    </label>

                <?php endforeach; ?>

            <?php endif; ?>

            <?php if ($quantidadeDocumentos === 0): ?>

                <p>
                    Nenhum documento disponível para mesclagem.
                </p>

            <?php endif; ?>

        </div>

        <div class="rodape-mesclagem">

            <p>
                Documentos selecionados:
                <strong id="contadorSelecionados">0</strong>
            </p>

            <button
                type="submit"
                id="botaoMesclar"
                <?= $quantidadeDocumentos === 0 ? 'disabled' : '' ?>
            >
                Mesclar PDFs
            </button>

        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const selecionarTodos = document.getElementById('selecionarTodos');

    const checkboxes = document.querySelectorAll('.documento-checkbox');

    const contador = document.getElementById('contadorSelecionados');

    const formulario = document.getElementById('formMesclarPdf');

    const botaoMesclar = document.getElementById('botaoMesclar');


    function atualizarContador() {

        const selecionados = document.querySelectorAll(
            '.documento-checkbox:checked'
        ).length;

        contador.textContent = selecionados;

        if (selecionarTodos) {
            selecionarTodos.checked =
                checkboxes.length > 0 &&
                selecionados === checkboxes.length;
        }
    }


    if (selecionarTodos) {

        selecionarTodos.addEventListener('change', function () {

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = selecionarTodos.checked;
            });

            atualizarContador();
        });
    }


    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', atualizarContador);
    });


    if (formulario) {

        formulario.addEventListener('submit', function (evento) {

            const selecionados = document.querySelectorAll(
                '.documento-checkbox:checked'
            ).length;

            if (selecionados === 0) {

                evento.preventDefault();

                alert('Selecione pelo menos um documento para mesclar.');

                return;
            }

            if (botaoMesclar) {

                botaoMesclar.disabled = true;

                botaoMesclar.textContent = 'Mesclando PDFs...';
            }
        });
    }

    atualizarContador();

});
</script>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/app.php';

?>