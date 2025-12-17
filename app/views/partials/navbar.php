<nav class="container-fluid navbar navbar-expand-lg">
    <div class="d-flex justify-content-around">

        <!--Toggler for mobile view-->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!--Logo of Thalassa-->
        <a class="navbar-brand mx-auto mx-lg-0" href="index.php">Thalassa Logo</a>

        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarResponsive">
            <!--Nabvar links-->
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?controller=novedades&action=index">NOVEDADES</a>
                </li>
                <!--Dropdown for the cart section-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CARTA</a>
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
            <a href="index.php?controller=cart&action=viewCart"><i class="bi bi-bag"></i></a>
        </div>

    </div>
</nav>

<!--Offcanvas for mobile view-->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="Offcanvas Navbar">
    <div class="offcanvas-header">
        <a class="navbar-brand" href="index.php">Thalassa Logo</a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item active">
                <a class="nav-link" href="index.php?controller=novedades&action=index">NOVEDADES</a>
            </li>
            <!--Dropdown for the cart section-->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CARTA</a>
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
        <br>
        
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="index.php?controller=user&action=showLogin">Iniciar sesión</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?controller=user&action=showRegister">Crear una cuenta</a></li>
            <!--Need to create the search bar offcanvas to set the link here-->
            <li class="nav-item"><a class="nav-link" href="index.php">Buscar</a></li>
        </ul>
    </div>

</div>