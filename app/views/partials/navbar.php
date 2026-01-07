<nav class="navbar navbar-expand-lg bg-secondary py-3 sticky-top">
    <div class="container-fluid px-4">

        <!--Toggler for mobile view-->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!--Logo of Thalassa-->
        <a class="navbar-brand mx-auto mx-lg-0" href="index.php">
            <img src="/assets/brand_logo/logo_mejorado_HQ.svg" alt="Thalassa Logo" class="navbar-logo">
        </a>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <!--Nabvar links-->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item <?= $page_id == "novedades" ? "active" : "" ?>">
                    <a class="nav-link h5-bold text-tertiary" href="index.php?controller=dish&action=indexNovedades">NOVEDADES</a>
                </li>
                <!--Dropdown for the cart section-->
                <li class="nav-item dropdown <?= $page_id == "carta" ? "active" : "" ?>">
                    <a class="nav-link dropdown-toggle h5-bold text-tertiary" href="#" id="navbarDropdownMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CARTA</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenu">
                        <a class="dropdown-item body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Ensaladas">Ensaladas</a>
                        <a class="dropdown-item body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Entrantes">Entrantes</a>
                        <a class="dropdown-item body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Principales">Principales</a>
                        <a class="dropdown-item body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Postres">Postres</a>
                        <a class="dropdown-item body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Bebidas">Bebidas</a>
                    </div>
                </li>
                <li class="nav-item <?= $page_id == "sobreNosotros" ? "active" : "" ?>">
                    <a class="nav-link h5-bold text-tertiary" href="index.php?controller=sobreNosotros&action=index">SOBRE NOSOTROS</a>
                </li>

            </ul>
        </div>

        <!--Navbar icons-->
        <div class="d-flex gap-1 ms-4 align-items-center">
            <a href="#">
                <img src="/assets/icons/search_icon.svg" alt="Search Icon" class="navbar-icon">
            </a>
            <a href="index.php?controller=user&action=redirectToConfigure">
                <img src="/assets/icons/user_icon.svg" alt="User Icon" class="navbar-icon d-none d-md-flex">
            </a>
            <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                <img src="/assets/icons/cart_icon.svg" alt="Cart Icon" class="navbar-icon">
            </a>
        </div>

    </div>
</nav>

<!--Offcanvas for mobile view-->
<div class="offcanvas offcanvas-start bg-secondary" tabindex="-1" id="mobileMenu" aria-labelledby="Offcanvas Navbar">
    <div class="offcanvas-header">
        <a class="navbar-brand offcanvas-brand" href="index.php">
            <img src="/assets/brand_logo/logo_mejorado_HQ.svg" alt="Thalassa Logo" class="navbar-logo">
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column list-unstyled">
            <li class="nav-item active">
                <a class="nav-link h5-bold text-tertiary" href="index.php?controller=dish&action=indexNovedades">NOVEDADES</a>
            </li>
            <!--Dropdown for the cart section-->
            <li class="nav-item dropdown">
                <a class="nav-link d-flex justify-content-between align-items-center h5-bold text-tertiary" href="#submenuCollapsed" id="navbarCollapsedMenu" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false">CARTA<span class="toggle-icon"></span></a>
                <div class="collapse" aria-labelledby="navbarCollapsedMenu" id="submenuCollapsed">
                    <a class="nav-link body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Ensaladas">Ensaladas</a>
                    <a class="nav-link body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Entrantes">Entrantes</a>
                    <a class="nav-link body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Principales">Principales</a>
                    <a class="nav-link body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Postres">Postres</a>
                    <a class="nav-link body-text-regular text-tertiary" href="index.php?controller=dish&action=index&category=Bebidas">Bebidas</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link h5-bold text-tertiary" href="index.php?controller=sobreNosotros&action=index">SOBRE NOSOTROS</a>
            </li>
        </ul>
        <br>

        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link body-text-regular text-tertiary" href="index.php?controller=user&action=showLogin">Iniciar sesión</a></li>
            <li class="nav-item"><a class="nav-link body-text-regular text-tertiary" href="index.php?controller=user&action=showRegister">Crear una cuenta</a></li>
            <!--Need to create the search bar offcanvas to set the link here-->
            <li class="nav-item"><a class="nav-link body-text-regular text-tertiary" href="index.php">Buscar</a></li>
        </ul>
    </div>

</div>