

<div class="container-fluid bg-secondary py-5 text-center">
    <!-- Header -->
    <h2 class="h2-extra-bold text-primary text-uppercase mb-2">El Mediterráneo es tuyo</h2>
    <p class="body-text-regular text-primary mb-4">¡Nuestros Best Sellers!</p>
    
    <!-- Tabs Navigation -->
    <ul class="nav justify-content-center mb-4 border-0" id="topicTabs" role="tablist">
        <li class="nav-item mx-3" role="presentation">
            <button class="nav-link active text-uppercase h5-bold text-primary bg-transparent border-0 p-0 ls-2" 
                    id="mar-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#mar-pane" 
                    type="button" 
                    role="tab" 
                    aria-controls="mar-pane" 
                    aria-selected="true">MAR</button>
        </li>
        <li class="nav-item mx-3" role="presentation">
            <button class="nav-link text-uppercase h5-bold text-tertiary bg-transparent border-0 p-0 ls-2" 
                    id="montaña-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#montaña-pane" 
                    type="button" 
                    role="tab" 
                    aria-controls="montaña-pane" 
                    aria-selected="false">MONTAÑA</button>
        </li>
    </ul>

    <!--Button to send to the cart. To be implemented..-->
    <div class="mb-5">
        <a href="#" class="btn btn-thalassa rounded-pill px-4 py-2 h5-bold text-uppercase btn-cta-topic">¡PRUÉBALOS YA!</a>
    </div>

    <!--Tab content with carousels-->
    <div class="tab-content container-fluid overflow-hidden px-0" id="topicTabsContent">
        <!--Topic Mar tab-->
        <div class="tab-pane fade show active" id="mar-pane" role="tabpanel" aria-labelledby="mar-tab">
            <?php if (!empty($chunksMar)): ?>
            <div id="carouselTopicMar" class="carousel carousel-dark slide" data-bs-ride="true" data-bs-touch="true">
                <div class="carousel-inner">
                    <?php foreach ($chunksMar as $index => $chunk): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="row g-3 px-2 px-md-5 justify-content-center mobile-scroll-row">
                                <?php foreach ($chunk as $dish): ?>
                                    <div class="col-6 col-md-4 col-lg-3 pb-5 pb-md-5">
                                        <div class="dish-image-wrapper h-100 w-100 mb-2" onmouseenter="startSlide(this)" onmouseleave="stopSlide(this)"
                                            data-images='<?= htmlspecialchars($dish->getImagePaths());
                                                            $firstImage = $dish->getImages()[0] ?? ['path' => '', 'alt' => 'Dish']; ?>'>

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

                                        <div class="dish-info text-start">
                                            <a href="#" class="card-dish-name text-decoration-none text-dark fw-semibold"><?= $dish->getDishName(); ?></a>
                                            &mdash;
                                            <a href="#" class="card-dish-price text-decoration-none text-dark"><?= $dish->getBasePrice(); ?> €</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(count($chunksMar) > 1): ?>
                    <button class="carousel-control-prev topic-carousel-control" type="button" data-bs-target="#carouselTopicMar" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next topic-carousel-control" type="button" data-bs-target="#carouselTopicMar" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                <?php endif; ?>
            </div>
            <?php else: ?>
                <p class="text-muted">No hay platos de Mar disponibles en este momento.</p>
            <?php endif; ?>
        </div>
        
        <!--Montaña topic tab-->
        <div class="tab-pane fade" id="montaña-pane" role="tabpanel" aria-labelledby="montaña-tab">
            <?php if (!empty($chunksMontaña)): ?>
            <div id="carouselTopicMontaña" class="carousel carousel-dark slide" data-bs-ride="true" data-bs-touch="true">
                <div class="carousel-inner">
                    <?php foreach ($chunksMontaña as $index => $chunk): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="row g-3 px-2 px-md-5 justify-content-center mobile-scroll-row">
                                <?php foreach ($chunk as $dish): ?>
                                    <div class="col-6 col-md-4 col-lg-3 pb-5 pb-md-5">
                                        <div class="dish-image-wrapper h-100 w-100 mb-2" onmouseenter="startSlide(this)" onmouseleave="stopSlide(this)"
                                            data-images='<?= htmlspecialchars($dish->getImagePaths());
                                                            $firstImage = $dish->getImages()[0] ?? ['path' => '', 'alt' => 'Dish']; ?>'>

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

                                        <div class="dish-info text-start">
                                            <a href="#" class="card-dish-name text-decoration-none text-dark fw-semibold"><?= $dish->getDishName(); ?></a>
                                            &mdash;
                                            <a href="#" class="card-dish-price text-decoration-none text-dark"><?= $dish->getBasePrice(); ?> €</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(count($chunksMontaña) > 1): ?>
                    <button class="carousel-control-prev topic-carousel-control" type="button" data-bs-target="#carouselTopicMontaña" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next topic-carousel-control" type="button" data-bs-target="#carouselTopicMontaña" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                <?php endif; ?>
            </div>
            <?php else: ?>
                <p class="text-muted">No hay platos de Montaña disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>