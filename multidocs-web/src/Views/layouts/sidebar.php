<?php
    $paginaAtual = $_GET['page'] ?? 'documentos';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<aside class="sidebar">

    <!-- Logo -->
    <div class="logo-area">
        <div class="logo-icon">MD</div>

        <div class="logo-text">
            MultiDocs
        </div>
    </div>

    <!-- Menu de navegação -->
    <nav class="menu">

        <a href="?page=documentos"
           class="menu-item <?= $paginaAtual === 'documentos' ? 'active' : '' ?>">

            <i class="bi bi-file-earmark-text"></i>
            <span>Meus Documentos</span>

        </a>

        <a href="?page=cadastrar-tipo"
           class="menu-item <?= $paginaAtual === 'cadastrar-tipo' ? 'active' : '' ?>">

            <i class="bi bi-tags"></i>
            <span>Categorias</span>

        </a>

        <a href="?page=mesclar-pdf"
           class="menu-item <?= $paginaAtual === 'mesclar-pdf' ? 'active' : '' ?>">

            <i class="bi bi-layers"></i>
            <span>Ferramentas PDF</span>

        </a>

        <a href="?page=historico"
           class="menu-item <?= $paginaAtual === 'historico' ? 'active' : '' ?>">

            <i class="bi bi-clock-history"></i>
            <span>Histórico</span>

        </a>

    </nav>

    <!-- Rodapé da sidebar -->
    <div class="sidebar-footer">

        <p>Armazenamento seguro e privado.</p>

        <small>
            Apenas você acessa seus arquivos.
        </small>

    </div>

</aside>