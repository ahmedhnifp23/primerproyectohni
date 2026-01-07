
document.addEventListener('DOMContentLoaded', function() {
    // Función para obtener parámetros URL
    const urlParams = new URLSearchParams(window.location.search);
    const successId = urlParams.get('order_success');
    const errorMsg = urlParams.get('error');
    
    // Referencias al DOM del modal
    const modalEl = document.getElementById('orderStatusModal');
    const modalIcon = document.getElementById('orderStatusIcon');
    const modalTitle = document.getElementById('orderStatusTitle');
    const modalMsg = document.getElementById('orderStatusMessage');
    
    // CASO 1: ÉXITO (Viene ?order_success=123)
    if (successId && modalEl) {
        // 1. Borrar carrito (Requisito clave)
        localStorage.removeItem('thalassa_order_lines');
        
        // 2. Configurar Modal de Éxito
        modalIcon.innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>';
        modalTitle.innerText = '¡Pedido Confirmado!';
        modalMsg.innerHTML = `Gracias por tu compra. Tu número de pedido es <strong>#${successId}</strong>.`;
        
        // 3. Mostrar Modal
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
        
        // 4. Limpiar URL para que al recargar no salga otra vez
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // CASO 2: ERROR (Viene ?error=Mensaje...)
    if (errorMsg && modalEl) {
        // 1. Configurar Modal de Error
        modalIcon.innerHTML = '<i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>';
        modalTitle.innerText = 'Algo salió mal';
        modalMsg.innerText = decodeURIComponent(errorMsg.replace(/\+/g, ' '));
        
        // 2. Botón para intentar de nuevo (cerrar modal)
        const btn = document.getElementById('orderStatusBtn');
        btn.innerText = "Cerrar e intentar de nuevo";
        btn.href = "#";
        btn.onclick = function() { bsModal.hide(); return false; };

        // 3. Mostrar Modal
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }
});
