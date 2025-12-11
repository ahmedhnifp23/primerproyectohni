<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thalassa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/../public/css/styles.css">
</head>

<body>

    <!-- Array with the views that should not iclude the header and footer -->
    <?php $viewsWithoutLayout = ['auth/login', 'auth/register'];
    $showLayout = true; ?>

    <?php if (isset($view)) {
        foreach ($viewsWithoutLayout as $viewWithoutLayout) {
            if (str_contains($view, $viewWithoutLayout)) {
                $showLayout = false;
            }
        }
    } ?>

    <?php if ($showLayout) require_once VIEWS_PATH . "partials/header.php"; ?>


    <?php if (isset($view) && file_exists($view)) {
        require_once $view;
    } else {
        //Include here the 404 page.
    } ?>

    <?php if ($showLayout) require_once VIEWS_PATH . "partials/footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>