<div class="auth-container">

    <!-- Painel esquerdo -->
    <section class="auth-brand-panel">

        <a href="?page=login" class="auth-brand">

            <div class="auth-brand-icon">MD</div>

            <span>MultiDocs</span>

        </a>


        <div class="auth-brand-content">

            <h1>
                Seus documentos,<br>
                organizados e prontos<br>
                quando você precisar.
            </h1>

            <div class="auth-benefits">

                <div class="auth-benefit">

                    <i class="bi bi-shield-check"></i>

                    <span>Armazenamento privado e seguro</span>

                </div>

                <div class="auth-benefit">

                    <i class="bi bi-search"></i>

                    <span>Busca e filtros por categoria e tipo</span>

                </div>

                <div class="auth-benefit">

                    <i class="bi bi-layers"></i>

                    <span>Mescle PDFs direto no navegador</span>

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

                <a href="?page=login" class="auth-tab active">
                    Entrar
                </a>

                <a href="?page=cadastro" class="auth-tab">
                    Criar conta
                </a>

            </div>


            <!-- Cabeçalho do formulário -->
            <div class="auth-form-heading">

                <h2>Bem-vindo de volta!</h2>

                <p>
                    Entre com suas credenciais para acessar seus documentos.
                </p>

            </div>


            <!-- Mensagem -->
            <?php if (!empty($mensagem)): ?>

                <div class="auth-message">

                    <i class="bi bi-exclamation-circle"></i>

                    <span>
                        <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                </div>

            <?php endif; ?>


            <!-- Formulário de login -->
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
                >


                <label for="password">
                    Senha
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Digite sua senha"
                    autocomplete="current-password"
                    required
                >


                <button type="submit" class="auth-button">

                    Entrar

                    <i class="bi bi-arrow-right"></i>

                </button>

            </form>


            <div class="auth-security-note">

                <i class="bi bi-shield-lock"></i>

                <span>
                    Acesse seus documentos com segurança.
                </span>

            </div>


        </div>

    </section>

</div>