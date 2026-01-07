const CartBridge = {
    storageKey: 'thalassa_order_lines',

    getLines: function () {
        return JSON.parse(localStorage.getItem(this.storageKey)) || [];
    },

    add: function (dishId, price) {
        let lines = this.getLines();
        lines.push({
            dish_id: parseInt(dishId),
            unit_price: parseFloat(price),
            notes: ''
        });
        localStorage.setItem(this.storageKey, JSON.stringify(lines));
        this.render();

        // Abrir el modal si usas Bootstrap 5
        const el = document.getElementById('offcanvasCart');
        if (el) {
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(el) || new bootstrap.Offcanvas(el);
            bsOffcanvas.show();
        }
    },

    remove: function (index) {
        let lines = this.getLines();
        lines.splice(index, 1);
        localStorage.setItem(this.storageKey, JSON.stringify(lines));
        this.render();
    },

    updateNote: function (index, text) {
        let lines = this.getLines();

        // Verificamos que el producto exista
        if (lines[index]) {
            lines[index].notes = text; // Actualizamos la nota
            localStorage.setItem(this.storageKey, JSON.stringify(lines));

            // NO llamamos a this.render() aquí para no cortar la escritura del usuario.
            // El dato ya está guardado para cuando le den a "Tramitar".
        }
    },

    render: function () {
        const lines = this.getLines();
        const container = document.getElementById('cart-dynamic-content'); // Asegúrate que el ID coincida con tu HTML

        // CAMBIO IMPORTANTE: Apuntamos al Front Controller
        // Usamos controller=Cart y action=render
        fetch('index.php?controller=cart&action=render', {
            method: 'POST',
            body: JSON.stringify({ order_lines: lines }),
            headers: { 'Content-Type': 'application/json' }
        })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(err => console.error(err));
    },

    // Función principal para cargar el resumen (acepta código opcional)
    loadSummaryForCheckout: function (discountCode = '') {
        const lines = this.getLines();
        const container = document.getElementById('order-summary-container');

        if (!container) return;

        // Enviamos líneas Y el código de descuento
        fetch('index.php?controller=Order&action=previewCart', {
            method: 'POST',
            body: JSON.stringify({
                lines: lines,
                discount_code: discountCode
            }),
            headers: { 'Content-Type': 'application/json' }
        })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(err => console.error(err));
    },

    // Nueva función para el botón "Aplicar"
    applyDiscount: function () {
        const input = document.getElementById('discount-input');
        if (input) {
            const code = input.value.trim();
            // Recargamos el resumen pasando el código
            this.loadSummaryForCheckout(code);
        }
    }
};

document.addEventListener('DOMContentLoaded', () => CartBridge.render());