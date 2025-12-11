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


<div class="register-wrapper d-flex justify-content-center align-items-center">
    <div class="row justify-content-center w-100">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card p-4 p-md-5 shadow">
                <h2 class="mb-4 text-center fw-bold text-primary">Crear una cuenta</h2>

                <form method="POST" action="index.php?controller=user&action=storeRegister">

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="first_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Tu nombre" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Tu apellido">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Elige un usuario" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="+34 ...">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="birth_date" class="form-label">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date">
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="agreementCheck" required>
                        <label class="form-check-label" for="agreementCheck">Acepto los términos y condiciones de Thalassa</label>
                    </div>

                    <button type="submit" class="btn btn-thalassa w-100 btn-lg">Registrarse</button>
                </form>

                <div class="mt-3 text-center">
                    <span class="text-muted">¿Ya tienes cuenta?</span>
                    <a href="index.php?controller=user&action=showLogin" class="text-primary text-decoration-none fw-bold">Inicia sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
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
    const shouldShowModal = <?= json_encode($showModal) ?>;
    if (shouldShowModal) {
        document.addEventListener('DOMContentLoaded', function() {
            const myModal = new bootstrap.Modal(document.getElementById('messageModal'));
            myModal.show();
        });
    }
</script>