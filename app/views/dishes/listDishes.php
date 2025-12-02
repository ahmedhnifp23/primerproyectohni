<h1>Nuestra carta</h1>
<?php
if (empty($dishes)): ?>
    <h2>No hay platos disponibles!!</h2>
<?php else: ?>
    <?php foreach ($dishes as $dish): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <?php $imagenes = $dish->getImages();
                $primeraImagen = !empty($imagenes) ? $imagenes[0] : [];
                ?>
                <img class="card-img-top" src="https://img.freepik.com/fotos-premium/manos-femeninas-sostienen-cubiertos-sobre-plato-vacio-naranja_185193-33404.jpg" alt="Imagen de prueba producto: <?php $dish->getDishName(); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $dish->getDishName();
                                            echo " ";
                                            echo $dish->getBasePrice(); ?> €</h5>
                    <p class="card-text"><?= $dish->getDishDescription() ?></p>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>