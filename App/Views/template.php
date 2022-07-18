<!DOCTYPE html>
<html lang="pt-br" style="min-height: 100%;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <link rel="shortcut icon" href="#">
    <link href="<?= URL_CSS ?>bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL_CSS ?>sidebar.css" rel="stylesheet">
    <link href="<?= URL_CSS ?>styles.css" rel="stylesheet">
    <link href="<?= URL_JS ?>sweetalert2/sweetalert2.css" rel="stylesheet">
    <link href="<?= FONTAWESOME ?>" rel="stylesheet">
    <title>App CompraVenda</title>
</head>

<body>
    <?php require_once 'App/Views/' . $view . '.php' ?>

    <script src="<?= URL_JS ?>bootstrap.min.js"></script>
    <script src="<?= URL_JS ?>sweetalert2/sweetalert2.js"></script>
</body>

</html>