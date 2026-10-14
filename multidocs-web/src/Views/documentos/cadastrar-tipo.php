<section class="categoria-container">

    <!-- Cabeçalho -->
    <header class="categoria-header">

        <div>
            <h1>Tipos de documentos</h1>

            <p>
                Organize seus arquivos cadastrando os tipos de documentos
                disponíveis no MultiDocs.
            </p>
        </div>

    </header>


    <!-- Mensagens de retorno -->
    <?php if (!empty($mensagem)): ?>

        <div class="categoria-alerta <?= $tipoMensagem === 'sucesso' ? 'alerta-sucesso' : 'alerta-erro' ?>">

            <i class="bi <?= $tipoMensagem === 'sucesso' ? 'bi-check-circle' : 'bi-exclamation-circle' ?>"></i>

            <span>
                <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
            </span>

        </div>

    <?php endif; ?>


    <!-- Cartão do formulário -->
    <div class="categoria-card">

        <div class="categoria-card-header">

            <div class="categoria-icon">
                <i class="bi bi-folder-plus"></i>
            </div>

            <div class="categoria-card-heading">

                <h2>Novo tipo de documento</h2>

                <p>
                    Preencha os dados para cadastrar um novo tipo.
                </p>

            </div>

        </div>


        <div class="categoria-card-body">

            <form method="POST" action="?page=cadastrar-tipo">

                <div class="categoria-form-group">

                    <label for="nome">
                        Nome do tipo de documento
                        <span class="campo-obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="nome"
                        id="nome"
                        class="categoria-input"
                        placeholder="Ex.: Certidão de nascimento"
                        maxlength="100"
                        required
                        value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <small class="categoria-ajuda">
                        Informe um nome claro para identificar o tipo de documento.
                    </small>

                </div>


                <!-- Ações -->
                <div class="categoria-acoes">

                    <button type="submit" class="categoria-btn categoria-btn-primary">

                        <i class="bi bi-plus-lg"></i>

                        Cadastrar tipo

                    </button>

                    <a href="?page=documentos" class="categoria-btn categoria-btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Voltar para documentos

                    </a>

                </div>

            </form>

        </div>

    </div>


    <!-- Informação auxiliar -->
    <div class="categoria-info">

        <i class="bi bi-info-circle"></i>

        <p>
            Cadastre tipos que facilitem a identificação e a organização
            dos documentos armazenados no MultiDocs.
        </p>

    </div>

</section>