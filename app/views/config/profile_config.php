<?php
//Include the special header for user confi page
require_once VIEWS_PATH . "partials/config_header.php";
?>

<div class="user-config-wrapper">
    <div class="container pt-5 pb-5">
        <h1 class="text-white mb-4 h3-bold" id="page-title">Perfil</h1>

        <!--Profile Section-->
        <div id="section-perfil">
            <form action="index.php?controller=user&action=updateProfile" method="POST">
                <!--User Info Section-->
                <div class="card border-0 rounded-3 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title h5-bold text-tertiary mb-0"><?php echo $firstName . ' ' . $lastName; ?></h5>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-tertiary small mb-1">Nombre</label>
                                <input type="text" name="first_name" class="form-control input-thalassa" value="<?php echo $firstName; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-tertiary small mb-1">Apellidos</label>
                                <input type="text" name="last_name" class="form-control input-thalassa" value="<?php echo $lastName; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-tertiary small mb-1">Correo electrónico</label>
                                <input type="email" name="email" class="form-control input-thalassa" value="<?php echo $email; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-tertiary small mb-1">Nombre de usuario</label>
                                <input type="text" name="username" class="form-control input-thalassa" value="<?php echo $username; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-tertiary small mb-1">Teléfono</label>
                                <input type="tel" name="phone" class="form-control input-thalassa" value="<?php echo $phone; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Address Section-->
                <div class="card border-0 rounded-3 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title h5-bold text-tertiary mb-0">Direcciones</h5>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="w-100">
                                    <h6 class="text-tertiary body-text-regular small mb-2">Dirección predeterminada</h6>

                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <input type="text" name="address[street]" class="form-control form-control-sm input-thalassa mb-2" placeholder="Calle" value="<?php echo $defaultAddr['street'] ?? ''; ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="address[apartment]" class="form-control form-control-sm input-thalassa mb-2" placeholder="Piso/Puerta" value="<?php echo $defaultAddr['apartment'] ?? ''; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="address[zip]" class="form-control form-control-sm input-thalassa" placeholder="CP" value="<?php echo $defaultAddr['zip'] ?? ''; ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="address[city]" class="form-control form-control-sm input-thalassa" placeholder="Ciudad" value="<?php echo $defaultAddr['city'] ?? ''; ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="address[province]" class="form-control form-control-sm input-thalassa" placeholder="Provincia" value="<?php echo $defaultAddr['province'] ?? ''; ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <select name="address[country]" class="form-select form-select-sm input-thalassa mt-2" required>
                                                <option value="ES" <?php echo (isset($defaultAddr['country']) && $defaultAddr['country'] === 'ES') ? 'selected' : ''; ?>>España</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-thalassa-inverse px-4 py-2 rounded-pill shadow-sm">Guardar cambios</button>
                </div>
            </form>
        </div>
        <!--End profile section-->


        <!-- Orders Section -->
        <div id="section-pedidos" style="display: none;">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body p-5 text-center">
                    <?php if (empty($orders)): ?>
                        <h5 class="fw-normal mb-3">Aún no tienes ningún pedido</h5>
                        <a href="index.php" class="text-decoration-none text-dark small fw-bold">Ve a la tienda para realizar un pedido.</a>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table text-start">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order->getOrderId(); ?></td>
                                            <td><?php echo $order->getOrderedAt(); ?></td>
                                            <td>
                                                <?php
                                                $status = $order->getOrderStatus();
                                                echo match ($status) {
                                                    1 => '<span class="badge bg-primary">En reparto</span>',
                                                    2 => '<span class="badge bg-success">Entregado</span>',
                                                    default => '<span class="badge bg-dark">Desconocido</span>'
                                                };
                                                ?>
                                            </td>
                                            <td><?php echo number_format($order->getTotalAmount(), 2); ?> €</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </div>
    <!--White line-->
    <div class="white-line-divider"></div>
    <div class="pb-4"></div>
</div>

<!--Feedback Modal that receives the array of feedback and prints it-->
<?php if (isset($feedback) && $feedback): ?>
    <div class="modal fade show modal-overlay-custom" id="profileFeedbackModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-body text-center p-5">
                    <i class="bi <?php echo $feedback['icon']; ?> <?php echo $feedback['color']; ?>" style="font-size: 4rem;"></i>
                    <h3 class="fw-bold mt-4 mb-3"><?php echo $feedback['title']; ?></h3>
                    <p class="text-muted mb-4 fs-5"><?php echo $feedback['message']; ?></p>
                    <button type="button" class="btn btn-warning rounded-pill px-5 py-2 fw-bold text-white shadow-sm" onclick="document.getElementById('profileFeedbackModal').style.display='none';">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>