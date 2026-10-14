<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>MultiDocs</title>

    <link rel="stylesheet" href="/multidocs-web/public/assets/css/app.css">

    <link rel="stylesheet" href="/multidocs-web/public/assets/css/sidebar.css">

    <link rel="stylesheet" href="/multidocs-web/public/assets/css/header.css">

    <link rel="stylesheet" href="/multidocs-web/public/assets/css/mesclar.css">

    <link rel="stylesheet" href="/multidocs-web/public/assets/css/documentos.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/multidocs-web/public/assets/css/categorias.css">
</head>

<body>

   <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">

        <?php include __DIR__ . '/header.php'; ?>

        <div class="page-content">

            <?= $content ?>

        </div>

        <?php include __DIR__ . '/footer.php'; ?>

    </div>

</body>

</html>