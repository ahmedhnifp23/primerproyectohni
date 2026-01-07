<?php require_once VIEWS_PATH . '/partials/checkout-header.php'; ?>

<div class="container-fluid bg-white w-100 h-100 p-0 m-0">
    <div class="row g-0">
        
        <div class="col-lg-7 px-3 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 text-primary">Contacto</h4>
                <?php if(!SessionManager::get('user_id')): ?>
                    <a href="#" class="small text-decoration-none text-primary">Iniciar sesión</a>
                <?php endif; ?>
            </div>
            
            <form id="checkout-form" action="index.php?controller=Order&action=process" method="POST" class="mb-5">
                
                <div class="mb-3">
                    <input type="email" class="form-control input-thalassa p-3" value="<?= htmlspecialchars($user->getEmail()) ?>" readonly placeholder="Correo electrónico">
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="newsletter" checked>
                    <label class="form-check-label small text-muted-thalassa" for="newsletter">
                        Enviarme novedades y ofertas por correo electrónico
                    </label>
                </div>

                <h4 class="mb-3 text-primary">Entrega</h4>

                <div class="mb-3">
                    <select class="form-select input-thalassa p-3" name="country">
                        <option value="España" <?= $country === 'España' ? 'selected' : '' ?>>España</option>
                        </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control input-thalassa p-3" name="name" placeholder="Nombre" 
                               value="<?= htmlspecialchars($user->getFirstName()) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control input-thalassa p-3" name="lastname" placeholder="Apellidos" 
                               value="<?= htmlspecialchars($user->getLastname()) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control input-thalassa p-3" name="street" placeholder="Dirección (Calle, número...)" 
                           value="<?= htmlspecialchars($street) ?>" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control input-thalassa p-3" name="apartment" placeholder="Casa, apartamento, etc." required 
                           value="<?= htmlspecialchars($apartment) ?>">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control input-thalassa p-3" name="zip" placeholder="Código postal" 
                               value="<?= htmlspecialchars($postalCode) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control input-thalassa p-3" name="city" placeholder="Ciudad" 
                               value="<?= htmlspecialchars($city) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select input-thalassa p-3" name="province">
                            <option value="">Provincia / Estado</option>
                            <option value="Barcelona" <?= $province === 'Barcelona' ? 'selected' : '' ?>>Barcelona</option>
                            <option value="Madrid" <?= $province === 'Madrid' ? 'selected' : '' ?>>Madrid</option>
                            <option value="<?= htmlspecialchars($province) ?>" selected><?= htmlspecialchars($province) ?></option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="position-relative">
                        <input type="tel" class="form-control input-thalassa p-3" name="phone" placeholder="Teléfono" 
                               value="<?= htmlspecialchars($user->getPhone()) ?>" required>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted-thalassa">
                            <i class="bi bi-question-circle"></i>
                        </span>
                    </div>
                </div>
                
                <h4 class="mb-3 text-primary">Pago</h4>
                <div class="alert alert-light border text-center p-4 bg-white">
                    <p class="mb-0 text-muted-thalassa small">Todas las transacciones son seguras y están encriptadas.</p>
                    <div class="mt-3">
                         <button type="button" class="btn btn-outline-primary w-100">Simulación de Pago con Tarjeta</button>
                    </div>
                </div>

                <input type="hidden" name="cart_json" id="hidden-cart-input">

                <input type="hidden" name="applied_discount_id" id="hidden-discount-id-input">

                <button class="btn btn-thalassa w-100 btn-lg py-3 mt-2 fw-bold" type="submit">Pagar ahora</button>
            </form>
            
            <div class="d-flex gap-3 text-muted-thalassa small mt-4 border-top pt-3">
                <a href="#" class="text-decoration-none text-muted-thalassa">Política de reembolso</a>
                <a href="#" class="text-decoration-none text-muted-thalassa">Política de envío</a>
                <a href="#" class="text-decoration-none text-muted-thalassa">Política de privacidad</a>
            </div>
        </div>

        <div class="col-lg-5 order-summary-wrapper">
            <div id="order-summary-container" class="h-100 w-100">
                <div class="p-5 text-center text-muted-thalassa bg-secondary w-100 h-100">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="assets/js/cart_bridge.js"></script>


<script>
    //Load the checkout summary once the DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        CartBridge.loadSummaryForCheckout();
    });
    //Get the cart lines and set them in a hidden input as JSON before submitting the form
    document.getElementById('checkout-form').addEventListener('submit', (e) => {
        const lines = CartBridge.getLines();
        
        if(lines.length === 0) {
            e.preventDefault();
            alert("Tu carrito está vacío.");
            return;
        }
       
        document.getElementById('hidden-cart-input').value = JSON.stringify(lines);
        
        //Get the applied discount from the summary and set it in a hidden input
        const summaryDiscountInput = document.getElementById('hidden-discount-id');
        if(summaryDiscountInput) {
            document.getElementById('hidden-discount-id-input').value = summaryDiscountInput.value;
        }
    });
</script>