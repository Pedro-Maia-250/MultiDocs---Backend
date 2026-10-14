<?php
$mensagem = $mensagem ?? '';
$tipoMensagem = $tipoMensagem ?? '';
$tiposDocumentos = $tiposDocumentos ?? [];
?>

<div class="upload-page">

    <div class="upload-page-header">
        <div>
            <h1>Enviar documento</h1>
            <p>Envie seus documentos para armazená-los no MultiDocs.</p>
        </div>

        <a href="?page=documentos" class="upload-back-button">
            Voltar para documentos
        </a>
    </div>

    <?php if ($mensagem !== ''): ?>
        <div class="upload-message <?= htmlspecialchars($tipoMensagem) ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <div class="upload-card">

        <div class="upload-card-header">
            <h2>Novo documento</h2>
            <p>Preencha as informações abaixo para continuar.</p>
        </div>

        <form
            action="?page=enviar-documento"
            method="POST"
            enctype="multipart/form-data"
            class="upload-form"
        >

            <div class="form-group">
                <label for="tipoId">Tipo de documento</label>

                <select
                    name="tipoId"
                    id="tipoId"
                    required
                >
                    <option value="">Selecione o tipo de documento</option>

                    <?php foreach ($tiposDocumentos as $tipo): ?>
                        <option
                            value="<?= (int) $tipo['id'] ?>"
                            <?= (
                                isset($_POST['tipoId']) &&
                                (int) $_POST['tipoId'] === (int) $tipo['id']
                            ) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($tipo['nome']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="arquivo">Arquivo PDF</label>

                <div class="upload-file-area">
                    <div class="upload-file-icon">PDF</div>

                    <div class="upload-file-description">
                        <strong>Selecione seu documento</strong>
                        <p>Escolha um arquivo PDF no seu computador.</p>
                    </div>

                    <input
                        type="file"
                        name="arquivo"
                        id="arquivo"
                        accept=".pdf,application/pdf"
                        required
                    >
                </div>

                <small class="form-help">
                    Formato permitido: PDF.
                </small>
            </div>

            <div class="upload-form-actions">
                <a href="?page=documentos" class="upload-cancel-button">
                    Cancelar
                </a>

                <button type="submit" class="upload-submit-button">
                    Enviar documento
                </button>
            </div>

        </form>

    </div>

</div>

<style>
.upload-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 24px;
    color: #1f2937;
}

.upload-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
}

.upload-page-header h1 {
    margin: 0 0 8px;
    font-size: 28px;
    font-weight: 700;
}

.upload-page-header p,
.upload-card-header p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}

.upload-back-button {
    color: #374151;
    text-decoration: none;
    font-size: 14px;
    white-space: nowrap;
}

.upload-back-button:hover {
    color: #2563eb;
}

.upload-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 28px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.upload-card-header {
    margin-bottom: 28px;
}

.upload-card-header h2 {
    margin: 0 0 8px;
    font-size: 20px;
}

.upload-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
}

.form-group select,
.form-group input[type="file"] {
    width: 100%;
    box-sizing: border-box;
    font-family: inherit;
    font-size: 14px;
}

.form-group select {
    padding: 12px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #374151;
}

.upload-file-area {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding: 20px;
    border: 1px dashed #9ca3af;
    border-radius: 10px;
    background: #f9fafb;
}

.upload-file-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 60px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #fee2e2;
    color: #b91c1c;
    font-weight: 700;
    font-size: 14px;
}

.upload-file-description {
    flex: 1;
    min-width: 180px;
}

.upload-file-description strong {
    font-size: 14px;
}

.upload-file-description p {
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.upload-file-area input[type="file"] {
    flex-basis: 100%;
}

.form-help {
    color: #6b7280;
    font-size: 12px;
}

.upload-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 8px;
}

.upload-cancel-button,
.upload-submit-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 0 18px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.upload-cancel-button {
    color: #374151;
    background: #fff;
    border: 1px solid #d1d5db;
}

.upload-submit-button {
    color: #fff;
    background: #2563eb;
    border: 1px solid #2563eb;
}

.upload-submit-button:hover {
    background: #1d4ed8;
}

.upload-message {
    margin-bottom: 20px;
    padding: 14px 16px;
    border-radius: 8px;
    font-size: 14px;
}

.upload-message.sucesso {
    color: #166534;
    background: #dcfce7;
    border: 1px solid #bbf7d0;
}

.upload-message.erro {
    color: #991b1b;
    background: #fee2e2;
    border: 1px solid #fecaca;
}

@media (max-width: 650px) {
    .upload-page {
        padding: 16px;
    }

    .upload-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .upload-card {
        padding: 20px;
    }

    .upload-form-actions {
        flex-direction: column-reverse;
    }

    .upload-cancel-button,
    .upload-submit-button {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>