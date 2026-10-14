<?php
session_start();
?>

<style>
    /* ==============================
       DASHBOARD MULTIDOCS
    ============================== */

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 10px;
    }

    /* Boas-vindas */

    .welcome-banner {
        position: relative;
        overflow: hidden;
        padding: 38px;
        margin-bottom: 30px;
        border-radius: 16px;
        background: linear-gradient(120deg, #1e3a8a, #2563eb);
        color: #ffffff;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);
    }

    .welcome-banner h1 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #ffffff;
    }

    .welcome-banner p {
        max-width: 600px;
        margin-bottom: 0;
        color: #dbeafe;
        font-size: 16px;
        line-height: 1.7;
    }

    .welcome-badge {
        display: inline-block;
        margin-bottom: 18px;
        padding: 7px 13px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Título das seções */

    .dashboard-section-title {
        margin-bottom: 8px;
        color: #1e293b;
        font-size: 22px;
        font-weight: 700;
    }

    .dashboard-section-description {
        margin-bottom: 22px;
        color: #64748b;
        font-size: 14px;
    }

    /* Cartões */

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 22px;
        margin-bottom: 30px;
    }

    .dashboard-card {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-height: 230px;
        padding: 25px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    }

    .dashboard-card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin-bottom: 20px;
        border-radius: 12px;
        background: #dbeafe;
        font-size: 25px;
    }

    .dashboard-card h3 {
        margin-bottom: 10px;
        color: #1e293b;
        font-size: 18px;
        font-weight: 700;
    }

    .dashboard-card p {
        flex-grow: 1;
        margin-bottom: 20px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    /* Links dos cartões */

    .dashboard-card-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #2563eb;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .dashboard-card-link:hover {
        color: #1d4ed8;
        text-decoration: none;
    }

    .dashboard-card-link .arrow {
        transition: transform 0.2s;
    }

    .dashboard-card-link:hover .arrow {
        transform: translateX(4px);
    }

    /* Informativo inferior */

    .dashboard-info {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 24px;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        background: #eff6ff;
    }

    .dashboard-info-icon {
        font-size: 25px;
    }

    .dashboard-info h4 {
        margin-bottom: 5px;
        color: #1e40af;
        font-size: 15px;
        font-weight: 700;
    }

    .dashboard-info p {
        margin-bottom: 0;
        color: #475569;
        font-size: 13px;
        line-height: 1.6;
    }

    /* Responsividade */

    @media (max-width: 576px) {
        .dashboard-container {
            padding: 12px 5px;
        }

        .welcome-banner {
            padding: 25px 22px;
        }

        .welcome-banner h1 {
            font-size: 24px;
        }

        .welcome-banner p {
            font-size: 14px;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .dashboard-card {
            min-height: 210px;
        }
    }
</style>

<div class="container-fluid dashboard-container">

    <!-- Boas-vindas -->

    <section class="welcome-banner">

        <span class="welcome-badge">
            ✨ SEU ESPAÇO DIGITAL
        </span>

        <h1>Bem-vindo ao MultiDocs!</h1>

        <p>
            Organize seus documentos em um só lugar.
            Envie arquivos, gerencie seus tipos de documentos
            e tenha tudo organizado de forma prática.
        </p>

    </section>

    <!-- Acesso rápido -->

    <section>

        <h2 class="dashboard-section-title">
            Acesso rápido
        </h2>

        <p class="dashboard-section-description">
            Escolha o que deseja fazer no sistema.
        </p>

        <div class="dashboard-grid">

            <!-- Meus documentos -->

            <article class="dashboard-card">

                <div class="dashboard-card-icon">
                    📄
                </div>

                <h3>Meus Documentos</h3>

                <p>
                    Consulte seus documentos cadastrados
                    e envie novos arquivos PDF.
                </p>

                <a href="?page=documentos" class="dashboard-card-link">
                    Acessar documentos
                    <span class="arrow">→</span>
                </a>

            </article>

            <!-- Categorias -->

            <article class="dashboard-card">

                <div class="dashboard-card-icon">
                    🏷️
                </div>

                <h3>Categorias</h3>

                <p>
                    Cadastre novos tipos de documentos
                    para manter seus arquivos organizados.
                </p>

                <a href="?page=cadastrar-tipo" class="dashboard-card-link">
                    Gerenciar categorias
                    <span class="arrow">→</span>
                </a>

            </article>

            <!-- Ferramentas PDF -->

            <article class="dashboard-card">

                <div class="dashboard-card-icon">
                    📚
                </div>

                <h3>Ferramentas PDF</h3>

                <p>
                    Em breve, você poderá acessar ferramentas
                    para trabalhar com seus arquivos PDF.
                </p>

                <span class="dashboard-card-link" style="color: #94a3b8;">
                    Disponível em breve
                </span>

            </article>

            <!-- Histórico -->

            <article class="dashboard-card">

                <div class="dashboard-card-icon">
                    🕒
                </div>

                <h3>Histórico</h3>

                <p>
                    Futuramente, consulte o histórico
                    das operações realizadas no sistema.
                </p>

                <span class="dashboard-card-link" style="color: #94a3b8;">
                    Disponível em breve
                </span>

            </article>

        </div>

    </section>

    <!-- Informação de segurança -->

    <section class="dashboard-info">

        <div class="dashboard-info-icon">
            🔒
        </div>

        <div>

            <h4>Seus documentos em um só lugar</h4>

            <p>
                Utilize o MultiDocs para organizar seus arquivos.
                O acesso aos documentos depende das permissões
                e dos mecanismos de segurança implementados no sistema.
            </p>

        </div>

    </section>

</div>