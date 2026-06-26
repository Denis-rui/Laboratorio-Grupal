<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi proyecto MVC</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/site.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <?php require_once 'header.php'; ?>

    <?php require_once 'navbar.php'; ?>
    <main>
        <?php require_once $contentView; ?>

    </main>
    <?php require_once 'footer.php'; ?>
    <script src="<?= BASE_URL ?>public/js/site.js"></script>
    <script src="<?= BASE_URL ?>public/js/filtros_productos.js" data-base-url="<?= BASE_URL ?>"></script>
</body>

</html>
