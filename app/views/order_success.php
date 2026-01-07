<?php require_once VIEWS_PATH . '/partials/checkout-header.php'; ?>

<div class="container py-5 text-center mt-100">
    <div class="card border-0 shadow-sm p-5 mx-auto mw-600">
        <div class="mb-4 text-success">
            <i class="bi bi-check-circle-fill fs-icon-xl"></i>
        </div>
        
        <h1 class="h2 fw-bold mb-3">¡Pedido Confirmado!</h1>
        <p class="text-muted-thalassa mb-4">
            Gracias por tu compra. Tu número de pedido es 
            <span class="fw-bold text-dark">#<?= htmlspecialchars($_GET['id'] ?? '') ?></span>.
        </p>
        
        <a href="index.php" class="btn btn-thalassa px-5 py-2 rounded-pill">Volver a la Carta</a>
    </div>
</div>

<script>
    //Clears the cart data from local storage after a successful purchase.
    document.addEventListener('DOMContentLoaded', () => {
        localStorage.removeItem('thalassa_order_lines');
        console.log('Carrito vaciado tras compra exitosa.');
    });
</script>
