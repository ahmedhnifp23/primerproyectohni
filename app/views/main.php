<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thalassa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">
</head>

<body class="main-body bg-dark">

    <!--Array with the views that should not iclude the header and footer-->
    <?php $viewsWithoutLayout = ['auth/login', 'auth/register', 'checkout_view', 'config/profile_config']; ?>
    <?php
    $showLayout = true; ?>

    <?php if (isset($view)) {
        foreach ($viewsWithoutLayout as $viewWithoutLayout) {
            if (str_contains($view, $viewWithoutLayout)) {
                $showLayout = false;
            }
        }
    } ?>

    <?php if ($showLayout) require_once VIEWS_PATH . "partials/announcement-slider.php"; ?>
    <?php if ($showLayout) require_once VIEWS_PATH . "partials/navbar.php"; ?>
    <?php if ($showLayout) require_once VIEWS_PATH . "partials/cart-base.php"; ?>

    <!--Modal to show succes or error during order creation-->
    <div class="modal fade" id="orderStatusModal" tabindex="-1" aria-labelledby="orderStatusLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">

                <div class="modal-body">
                    <div id="orderStatusIcon" class="mb-3"></div>
                    <h3 id="orderStatusTitle" class="fw-bold mb-2"></h3>
                    <p id="orderStatusMessage" class="text-muted mb-4"></p>

                    <a href="index.php" class="btn btn-dark w-100 py-2 rounded-pill" id="orderStatusBtn">Volver a la tienda</a>
                </div>

            </div>
        </div>
    </div>

    <?php if (isset($view) && file_exists($view)) {
        require_once $view;
    } else {
        require_once VIEWS_PATH . "/404.php";
    } ?>

    <?php if ($showLayout) require_once VIEWS_PATH . "partials/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/cart_bridge.js"></script>
    <script src="/assets/js/order_status_modal.js"></script>
</body>

</html>