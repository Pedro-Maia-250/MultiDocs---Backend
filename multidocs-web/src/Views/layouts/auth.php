<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title ?? 'MultiDocs', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <!-- Biblioteca de ícones -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- Estilos da autenticação -->
    <link
        rel="stylesheet"
        href="/multidocs-web/public/assets/css/auth.css"
    >

</head>

<body>

    <?php include $view; ?>

</body>

</html>