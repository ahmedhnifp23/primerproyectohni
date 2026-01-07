<?php
if (session_status() == PHP_SESSION_NONE) {
    SessionManager::start();
}

$modalTitle = "";
$modalBody = "";
$modalType = "";
$showModal = false;

if ($error = SessionManager::get('error_register')) {
    $modalTitle = "Error de Registro";
    $modalBody = $error;
    $modalType = "danger";
    $showModal = true;
    SessionManager::remove('error_register');
}
?>


<div class="register-wrapper d-flex justify-content-center align-items-center py-5">
    <div class="row justify-content-center w-100 m-0">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card p-4 p-md-5 shadow rounded-4">
                
                <div class="text-center mb-4">
                    <a href="index.php">
                        <img src="/assets/brand_logo/logo_mejorado_HQ.svg" alt="Thalassa Logo" class="navbar-logo mb-3">
                    </a>
                    <h2 class="h2-extra-bold text-primary">¡Regístrate y únete a Thalassa!</h2>
                </div>

                <form method="POST" action="index.php?controller=user&action=storeRegister">

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="first_name" class="form-label h5-bold text-primary">Nombre</label>
                            <input type="text" class="form-control input-thalassa" id="first_name" name="first_name" placeholder="Tu nombre" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="last_name" class="form-label h5-bold text-primary">Apellido</label>
                            <input type="text" class="form-control input-thalassa" id="last_name" name="last_name" placeholder="Tu apellido">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label h5-bold text-primary">Correo electrónico</label>
                        <input type="email" class="form-control input-thalassa" id="email" name="email" placeholder="nombre@ejemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label h5-bold text-primary">Nombre de usuario</label>
                        <input type="text" class="form-control input-thalassa" id="username" name="username" placeholder="Elige un nombre de usuario" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label h5-bold text-primary">Contraseña</label>
                        <input type="password" class="form-control input-thalassa" id="password" name="password" placeholder="********" required>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="phone" class="form-label h5-bold text-primary">Teléfono</label>
                            <input type="text" class="form-control input-thalassa" id="phone" name="phone" placeholder="+34 ...">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="birth_date" class="form-label h5-bold text-primary">Fecha de nacimiento</label>
                            <input type="date" class="form-control input-thalassa" id="birth_date" name="birth_date">
                        </div>
                    </div>

                    <h3 class="mt-4 mb-3 h3-bold text-primary">Dirección</h3>
                    <div class="mb-3">
                        <label for="street" class="form-label h5-bold text-primary">Calle</label>
                        <input type="text" class="form-control input-thalassa" id="street" name="street" placeholder="Nombre de la calle" required>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                           <label for="apartment" class="form-label h5-bold text-primary">Casa / Piso</label>
                           <input type="text" class="form-control input-thalassa" id="apartment" name="apartment" placeholder="nº / Bloque A, 2º 1ª">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="zip" class="form-label h5-bold text-primary">Código Postal</label>
                            <input type="text" class="form-control input-thalassa" id="zip" name="zip" placeholder="08001" required>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-12 col-md-4 mb-3">
                            <label for="city" class="form-label h5-bold text-primary">Ciudad</label>
                            <input type="text" class="form-control input-thalassa" id="city" name="city" placeholder="Barcelona" required>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="province" class="form-label h5-bold text-primary">Provincia</label>
                            <input type="text" class="form-control input-thalassa" id="province" name="province" placeholder="Barcelona" required>
                        </div>
                         <div class="col-12 col-md-4 mb-3">
                            <label for="country" class="form-label h5-bold text-primary">País</label>
                            <input type="text" class="form-control input-thalassa" id="country" name="country" placeholder="España" required>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input input-thalassa" id="agreementCheck" required>
                        <label class="form-check-label body-text-regular text-primary" for="agreementCheck">Acepto los términos y condiciones de Thalassa</label>
                    </div>

                    <button type="submit" class="btn btn-thalassa w-100 btn-lg mb-3">Registrarse</button>
                </form>

                <div class="mt-3 text-center">
                    <span class="body-text-regular text-primary">¿Ya tienes cuenta?</span>
                    <a href="index.php?controller=user&action=showLogin" class="text-primary text-decoration-none h5-bold">Inicia sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Modal to show error or succes in login-->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true" data-show="<?= $showModal ? 'true' : 'false' ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-<?= $modalType ?> text-white">
                <h5 class="modal-title"><?= $modalTitle ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $modalBody ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('messageModal');

        //Read data-show attribute and if its true show the modal
        if (modalEl && modalEl.dataset.show === 'true') {
            const myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        }
    });
</script>