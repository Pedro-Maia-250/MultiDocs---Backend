<div class="auth-container">

    <!-- Painel esquerdo -->
    <section class="auth-brand-panel">

        <a href="?page=login" class="auth-brand">

            <div class="auth-brand-icon">MD</div>

            <span>MultiDocs</span>

        </a>


        <div class="auth-brand-content">

            <h1>
                Organize seus<br>
                documentos com<br>
                praticidade e segurança.
            </h1>

            <div class="auth-benefits">

                <div class="auth-benefit">

                    <i class="bi bi-folder2-open"></i>

                    <span>Todos os seus documentos organizados</span>

                </div>

                <div class="auth-benefit">

                    <i class="bi bi-shield-check"></i>

                    <span>Privacidade e segurança dos seus arquivos</span>

                </div>

                <div class="auth-benefit">

                    <i class="bi bi-file-earmark-pdf"></i>

                    <span>Gerencie e visualize seus PDFs</span>

                </div>

            </div>

        </div>


        <div class="auth-brand-footer">
            Gerenciador de Documentos Digitais
        </div>

    </section>


    <!-- Painel direito -->
    <section class="auth-form-panel">

        <div class="auth-card">

            <!-- Abas -->
            <div class="auth-tabs">

                <a href="?page=login" class="auth-tab">
                    Entrar
                </a>

                <a href="?page=cadastro" class="auth-tab active">
                    Criar conta
                </a>

            </div>


            <!-- Cabeçalho -->
            <div class="auth-form-heading">

                <h2>Crie sua conta</h2>

                <p>
                    Preencha os dados abaixo para começar a utilizar o MultiDocs.
                </p>

            </div>


            <!-- Mensagem -->
            <?php if (!empty($mensagem)): ?>

    <?php
        $cadastroSucesso = ($tipoMensagem ?? '') === 'sucesso';
    ?>

    <div class="auth-message <?= $cadastroSucesso ? 'auth-message-success' : 'auth-message-error' ?>">

        <i class="bi <?= $cadastroSucesso ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' ?>"></i>

        <span>
            <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
        </span>

    </div>

<?php endif; ?>

            <!-- Formulário de cadastro -->
            <form method="POST" class="auth-form">

                <label for="username">
                    E-mail ou usuário
                </label>

                <input
                    type="text"
                    name="username"
                    id="username"
                    placeholder="Digite seu e-mail ou usuário"
                    autocomplete="username"
                    required
                    value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >


                <label for="password">
                    Senha
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Crie uma senha"
                    autocomplete="new-password"
                    required
                >


                <button type="submit" class="auth-button">

                    Criar conta

                    <i class="bi bi-arrow-right"></i>

                </button>

            </form>


            <div class="auth-security-note">

                <i class="bi bi-shield-lock"></i>

                <span>
                    Seus documentos merecem um lugar seguro.
                </span>

            </div>


        </div>

    </section>

</div>