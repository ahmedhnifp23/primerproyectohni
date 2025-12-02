<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestra carta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once VIEWS_PATH . "partials/header.php";?>


    <?php if(isset($view) && file_exists($view)){
        require_once $view;
    } ?>


    <?php require_once VIEWS_PATH . "partials/footer.php";?>
</body>

</html>