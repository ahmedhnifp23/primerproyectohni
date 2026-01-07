import { buildApiUrl, Config } from '../config.js';

export class Order {

    static endpoint = 'order';

    constructor(order_id, user_id, discount_id, delivery_addr, ordered_at, total_amount, order_status, rating, notes) {
        this.order_id = order_id;
        this.user_id = user_id;
        this.discount_id = discount_id;
        this.delivery_addr = delivery_addr;
        this.ordered_at = ordered_at;
        this.total_amount = total_amount;
        this.order_status = order_status;
        this.rating = rating;
        this.notes = notes;
    }

    /**
     * Static async method to get all the orders.
     * @returns {Promise<Order[]>}
     */
    static async getAllOrders() {
        try {
            const url = buildApiUrl(this.endpoint);
            console.log('Fetching orders from URL:', url);
            const response = await fetch(url);

            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
            const json = await response.json();
            
            if (json.data && Array.isArray(json.data)) {
                return json.data.map(o => new Order(
                    o.order_id,
                    o.user_id,
                    o.discount_id,
                    o.delivery_addr,
                    o.ordered_at,
                    o.total_amount,
                    o.order_status,
                    o.rating,
                    o.notes
                ));
            }
            return [];

        } catch (error) {
            console.error('Error fetching orders:', error);
            throw error;
        }
    }

    /**
     * Returns httml table row of the order
     * @param {number} rate exchange rate to apply (default 1)
     * @param {string} symbol currency symbol (default €)
     */
    toHtmlRow(rate = 1, symbol = '€') {
        const amount = (parseFloat(this.total_amount) * rate).toFixed(2);
        return `
        <tr>
            <td>${this.order_id}</td>
            <td>User #${this.user_id}</td>
            <td>${this.ordered_at}</td>
            <td><span class="badge bg-secondary">${this.getStatusLabel()}</span></td>
            <td class="text-end">${amount} ${symbol}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" onclick="editOrder(${this.order_id})"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm" onclick="deleteOrder(${this.order_id})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `;
    }
    //Helpter to get the status label
    getStatusLabel() {
        switch(parseInt(this.order_status)) {
            case 1: return 'Pagado';
            case 2: return 'Pagado y Enviado';
            default: return 'Desconocido';
        }
    }

    //Async method to get an order by id
    static async getById(id){
        try{
            const url = buildApiUrl(this.endpoint, { order_id: id });
            const response = await fetch(url);
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }

            const json = await response.json();
            console.log('Fetched order data:', json);
            if(json.data){
                return new Order(
                    json.data.order_id,
                    json.data.user_id,
                    json.data.discount_id,
                    json.data.delivery_addr,
                    json.data.ordered_at,
                    json.data.total_amount,
                    json.data.order_status,
                    json.data.rating,
                    json.data.notes
                );
            }
        } catch(error){
            console.error('Error fetching order by id:', error);
            throw error;
        }
    }
    //Function to delete an order
    static async delete(id){
        const url = buildApiUrl(this.endpoint, {order_id: id});
        try{
            const response = await fetch(url,{ method: 'DELETE' });
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
        } catch(error){
            console.error('Error deleting order:', error);
            throw error;
        }
        return true;
    }
    //Function to update an order
    static async update(formData){
        console.log('Updating order:', formData);
        const url = buildApiUrl(this.endpoint);
        try{
            const response = await fetch(url,{ method: 'POST', body: formData });
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
        } catch(error){
            console.error('Error updating order:', error);
            throw error;
        }
        return true;
    }
    //Function to create an order
    static async create(formData){
        console.log('Creating order:', formData);
        const url = buildApiUrl(this.endpoint);
        try{
            const response = await fetch(url,{ method: 'POST', body: formData });
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
        } catch(error){
            console.error('Error creating order:', error);
            throw error;
        }
        return true;
    }
}
