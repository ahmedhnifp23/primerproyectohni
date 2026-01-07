<!--Wrapper of the menu page-->
<div class="container-fluid menu-page-wrapper bg-secondary pb-3">
    <div class="container-fluid h-100 bg-secondary py-4 px-2">
        
        <!--Div for breadcrumbs and dishes count-->
        <div class="d-flex justify-content-between align-items-center">
            <!--Breadcrumbs-->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="index.php" class="link-text-regular text-primary">Inicio</a></li>
                    <li class="breadcrumb-item active body-text-regular text-tertiary" aria-current="page"><?= htmlspecialchars($page_title) ?></li>
                </ol>
            </nav>
            <!--Dishes count-->
            <div class="dishes-count">
                <span><?= count($dishes) ?> productos</span>
            </div>
        </div>

        <!--Div for the dishes list-->
        <div class="row g-3">

            <?php foreach ($dishes as $dish) : ?>
                <div class="col-6 col-md-4 col-lg-3 pb-5 pb-md-5">
                    <div class="dish-image-wrapper h-100 w-100 mb-2" onmouseenter="startSlide(this)" onmouseleave="stopSlide(this)"
                        data-images='<?= htmlspecialchars($dish->getImagePaths());
                                        $firstImage = $dish->getImages()[0]; ?>'>

                        <a href="#"><img src="<?= $firstImage['path'] ?>" alt="<?= $firstImage['alt'] ?>" class="card-dish-image first-image img-fluid rounded-3"></a>

                        <a href="#"
                            class="btn btn-card-dish d-flex align-items-center justify-content-evenly"
                            onclick="CartBridge.add(<?= $dish->getDishId() ?>, <?= $dish->getBasePrice() ?>); return false;">

                            <img class="dish-card-btn-icon me-2" src="/assets/icons/cart_icon.svg" alt="Icono de carrito">
                            <span class="dish-card-hidden-btn-text me-2"> Compra rápida</span>
                        </a>
                        <div class="progress-container">
                            <div class="progress-bar"></div>
                        </div>
                    </div>

                    <div class="dish-info">
                        <a href="#" class="card-dish-name text-decoration-none text-dark fw-semibold"><?= $dish->getDishName(); ?></a>
                        &mdash;
                        <a href="#" class="card-dish-price text-decoration-none text-dark"><?= $dish->getBasePrice(); ?> €</a>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>


    </div>



</div>