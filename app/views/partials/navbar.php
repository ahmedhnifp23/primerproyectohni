<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">

        <!--Logo of Thalassa-->
        <a class="navbar-brand" href="index.php">Thalassa Logo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <!--Nabvar links-->
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?controller=novedades&action=index">NOVEDADES</a>
                </li>
                <!--Dropdown for the cart section-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CARTA</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenu">
                        <a class="dropdown-item" href="">Ensaladas</a>
                        <a class="dropdown-item" href="">Entrantes</a>
                        <a class="dropdown-item" href="">Principales</a>
                        <a class="dropdown-item" href="">Postres</a>
                        <a class="dropdown-item" href="">Bebidas</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=sobreNosotros&action=index">SOBRE NOSOTROS</a>
                </li>

            </ul>
        </div>

        <!--Navbar icons-->
        <div class="d-flex gap-3">
            <a href="index.php?controller=search&action=index"><i class="bi bi-search"></i></a>
            <a href="index.php?controller=user&action=profile"><i class="bi bi-person"></i></a>
            <a href="index.php?controller=cart&action=viewCart"><i class="bi bi-cart"></i></a>
        </div>

    </div>
</nav>