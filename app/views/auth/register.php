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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thalassa Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-primary">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card p-4 p-md-5 shadow"> <h2 class="mb-4 text-center fw-bold text-primary">Crear una cuenta</h2>
                    
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

                        <button type="submit" class="btn btn-primary w-100 btn-lg">Registrarse</button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>¿Ya tienes cuenta? <a href="index.php?controller=user&action=showLogin">Inicia sesión</a></p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const shouldShowModal = <?= json_encode($showModal) ?>;
        if (shouldShowModal) {
            document.addEventListener('DOMContentLoaded', function() {
                const myModal = new bootstrap.Modal(document.getElementById('messageModal'));
                myModal.show();
            });
        }
    </script>

</body>
</html>