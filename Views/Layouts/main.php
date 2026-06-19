<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi proyecto MVC</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/site.css">
</head>

<body>
    <?php require_once 'header.php'; ?>

    <?php require_once 'navbar.php'; ?>
    <main>
        <?php require_once $contentView; ?>

    </main>
    <?php require_once 'footer.php'; ?>
    <script src="<?= BASE_URL ?>public/js/site.js"></script>
</body>

</html>
