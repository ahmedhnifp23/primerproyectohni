
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const successId = urlParams.get('order_success');
    const errorMsg = urlParams.get('error');
    

    const modalEl = document.getElementById('orderStatusModal');
    const modalIcon = document.getElementById('orderStatusIcon');
    const modalTitle = document.getElementById('orderStatusTitle');
    const modalMsg = document.getElementById('orderStatusMessage');
    

    if (successId && modalEl) {

        localStorage.removeItem('thalassa_order_lines');
        
        modalIcon.innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>';
        modalTitle.innerText = '¡Pedido Confirmado!';
        modalMsg.innerHTML = `Gracias por tu compra. Tu número de pedido es <strong>#${successId}</strong>.`;
        
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
        

        window.history.replaceState({}, document.title, window.location.pathname);
    }


    if (errorMsg && modalEl) {
        modalIcon.innerHTML = '<i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>';
        modalTitle.innerText = 'Algo salió mal';
        modalMsg.innerText = decodeURIComponent(errorMsg.replace(/\+/g, ' '));
        
        const btn = document.getElementById('orderStatusBtn');
        btn.innerText = "Cerrar e intentar de nuevo";
        btn.href = "#";
        btn.onclick = function() { bsModal.hide(); return false; };

        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }
});
