<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestra carta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h1>Nuestra carta</h1>
        <div class="row">
            <?php var_dump($dishes);
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








        </div>
    </div>

</body>

</html>