<?php
$totalDocumentosEnviados = 0;

foreach ($documentos as $item) {
    if (!empty($item['documento'])) {
        $totalDocumentosEnviados++;
    }
}
?>

<section class="documentos-page">

    <!-- Cabeçalho da página -->
    <header class="documentos-header">

        <div class="documentos-heading">
            <h1>Meus Documentos</h1>

            <p>
                <?= $totalDocumentosEnviados ?>
                documento(s) armazenado(s)
            </p>
        </div>

        <a href="?page=mesclar-pdf" class="btn-documentos btn-mesclar">
            <i class="bi bi-layers"></i>
            <span>Mesclar PDFs</span>
        </a>

    </header>


    <!-- Lista de documentos -->
    <?php if (empty($documentos)): ?>

        <div class="documentos-empty">

            <i class="bi bi-file-earmark-text empty-icon"></i>

            <h2>Nenhum documento disponível</h2>

            <p>
                Não há tipos de documentos disponíveis para exibição.
            </p>

        </div>

    <?php else: ?>

        <div class="documentos-grid">

            <?php foreach ($documentos as $item): ?>

                <?php
                    $tipo = $item['tipo'] ?? [];
                    $documento = $item['documento'] ?? null;

                    $nomeTipo = $tipo['nome'] ?? 'Documento';
                    $identificacaoTipo = $tipo['tipo'] ?? 'Não informado';
                    $tipoId = (int) ($tipo['id'] ?? 0);

                    $enviado = !empty($documento);
                    $documentoId = (int) ($documento['id'] ?? 0);
                    $dataDocumento = $documento['data'] ?? 'Não informada';
                ?>

                <article class="documento-card">

                    <!-- Ícone e informações -->
                    <div class="documento-card-top">

                        <div class="documento-icon">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>

                        <span class="documento-status <?= $enviado ? 'status-enviado' : 'status-pendente' ?>">

                            <span class="status-indicator"></span>

                            <?= $enviado ? 'Enviado' : 'Pendente' ?>

                        </span>

                    </div>


                    <!-- Informações do documento -->
                    <div class="documento-info">

                        <h2>
                            <?= htmlspecialchars($nomeTipo, ENT_QUOTES, 'UTF-8') ?>
                        </h2>

                        <p class="documento-tipo">
                            Tipo:
                            <span>
                                <?= htmlspecialchars($identificacaoTipo, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </p>

                    </div>


                    <!-- Data de envio -->
                    <div class="documento-data">

                        <i class="bi bi-calendar3"></i>

                        <span>
                            <?php if ($enviado): ?>

                                Enviado em:
                                <?= htmlspecialchars($dataDocumento, ENT_QUOTES, 'UTF-8') ?>

                            <?php else: ?>

                                Aguardando envio

                            <?php endif; ?>
                        </span>

                    </div>


                    <!-- Ações -->
                    <div class="documento-actions">

                        <?php if ($enviado): ?>

                            <a
                                href="?page=visualizar-documento&id=<?= $documentoId ?>"
                                class="btn-documentos btn-visualizar"
                            >
                                <i class="bi bi-eye"></i>
                                Visualizar PDF
                            </a>

                        <?php else: ?>

                            <a
                                href="?page=enviar-documento&tipoId=<?= $tipoId ?>"
                                class="btn-documentos btn-enviar"
                            >
                                <i class="bi bi-upload"></i>
                                Enviar documento
                            </a>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>