<?php
if (session_status() == PHP_SESSION_NONE) {
    SessionManager::start();
}

$modalTitle = "";
$modalBody = "";
$modalType = ""; 
$showModal = false;

if ($error = SessionManager::get('error_login')) {
    $modalTitle = "Error de Identificación";
    $modalBody = $error;
    $modalType = "danger";
    $showModal = true;
    SessionManager::remove('error_login');
} 

elseif ($success = SessionManager::get('success_register')) {
    $modalTitle = "¡Registro Exitoso!";
    $modalBody = $success;
    $modalType = "success";
    $showModal = true;
    SessionManager::remove('success_register');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thalassa Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #296ACE;
            height: 100vh;
        }
        .login-card {
            background-color: white;
            width: 450px;
            min-height: 470px;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .btn-thalassa {
            background-color: #296ACE;
            color: white;
            font-weight: 600;
        }
        .btn-thalassa:hover {
            background-color: #1e54a8;
            color: white;
        }
    </style>
</head>

<body class="container d-flex justify-content-center align-items-center">

    <div class="login-card card p-4 d-flex flex-column justify-content-center">
        
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Thalassa</h2>
            <p class="text-muted">Bienvenido de nuevo</p>
        </div>

        <form action="index.php?controller=user&action=storeLogin" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="usuario@ejemplo.com" required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Contraseña</label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="********" required>
            </div>

            <button type="submit" class="btn btn-thalassa btn-lg w-100 mb-3">Iniciar Sesión</button>
        </form>

        <div class="text-center mt-2">
            <span class="text-muted">¿No tienes cuenta?</span>
            <a href="index.php?controller=user&action=showRegister" class="text-primary text-decoration-none fw-bold">Regístrate aquí</a>
        </div>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-<?= $modalType ?> text-white">
                    <h5 class="modal-title" id="modalLabel"><?= $modalTitle ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="fs-5"><?= $modalBody ?></p>
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
                var myModal = new bootstrap.Modal(document.getElementById('messageModal'));
                myModal.show();
            });
        }
    </script>

</body>
</html>