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

        if (lines[index]) {
            lines[index].notes = text; 
            localStorage.setItem(this.storageKey, JSON.stringify(lines));


        }
    },

    render: function () {
        const lines = this.getLines();
        const container = document.getElementById('cart-dynamic-content'); 

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

    loadSummaryForCheckout: function (discountCode = '') {
        const lines = this.getLines();
        const container = document.getElementById('order-summary-container');

        if (!container) return;

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

    applyDiscount: function () {
        const input = document.getElementById('discount-input');
        if (input) {
            const code = input.value.trim();
            this.loadSummaryForCheckout(code);
        }
    }
};

document.addEventListener('DOMContentLoaded', () => CartBridge.render());