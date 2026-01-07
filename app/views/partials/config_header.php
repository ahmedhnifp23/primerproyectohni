<nav class="navbar navbar-expand-lg bg-white py-3 border-bottom navbar-config">
    <div class="container">

        <!--Logo-->
        <a class="navbar-brand me-5" href="index.php">
            <img src="/assets/brand_logo/logo_mejorado_HQ.svg" alt="Thalassa Logo" class="navbar-logo">
        </a>

        <!--Nav link tabs-->
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link h5-bold text-tertiary" href="index.php">Tienda</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link h5-bold text-tertiary" href="#" onclick="showSection('pedidos'); return false;">Pedidos</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link h5-bold text-tertiary" href="#" onclick="showSection('perfil'); return false;">Perfil</a>
                </li>
            </ul>

            <!--User dropdown-->
            <div class="dropdown">
                <button class="btn bg-white rounded-pill h5-bold text-tertiary border-0 d-flex align-items-center gap-2 py-1 pe-3 ps-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm user-avatar-small">
                        <?php 
                            $initials = "U";
                            if(isset($user)) {
                                $initials = strtoupper(substr($user->getFirstName(), 0, 1) . substr($user->getLastName() ?? '', 0, 1));
                            }
                            echo $initials;
                        ?>
                    </div>
                    <i class="bi bi-chevron-down small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm p-2 mt-2 user-dropdown-menu">
                    <li class="px-3 py-2">
                        <div class="h5-bold text-primary"><?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?></div>
                        <div class="body-text-regular text-tertiary small"><?php echo $user->getEmail(); ?></div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item body-text-regular text-tertiary py-2 rounded" href="#" onclick="showSection('perfil'); return false;">Perfil</a></li>
                    <li><a class="dropdown-item body-text-regular py-2 rounded text-danger" href="index.php?controller=user&action=logout">Cerrar sesión</a></li>
                </ul>
            </div>

        </div>
    </div>
</nav>

<script>
function showSection(sectionId) {
    //Hide all sections
    document.getElementById('section-perfil').style.display = 'none';
    document.getElementById('section-pedidos').style.display = 'none';
    
    //Show the selected section
    if (sectionId === 'perfil') {
        document.getElementById('section-perfil').style.display = 'block';
        document.getElementById('page-title').innerText = 'Perfil';
    } else if (sectionId === 'pedidos') {
        document.getElementById('section-pedidos').style.display = 'block';
        document.getElementById('page-title').innerText = 'Pedidos';
    }
}
</script>
