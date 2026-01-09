import { Dish } from './classes/Dish.js';
import { Order } from './classes/Order.js';
import { Logs } from './classes/Logs.js';

//We wait for the DOM to be loaded
document.addEventListener('DOMContentLoaded', () => {
    setupNavigation();
    setupGlobalFunctions();
    setupModalListeners();
    setupOrderFilters();
    setupCurrencyToggle();
    loadDishes();
});

//Variable to save the id of the dish/order to be deleted
let toDeleteDish = null;
let toDeleteOrder = null;
//Variable to save the images to delete when editing a dish
let imagesToDelete = [];
//Variable to store all orders
let allOrders = [];
//Variables for currency conversion
let currentRate = 1;
let currentSettings = { rate: 1, symbol: '€' };

async function loadDishes() {
    const tableBody = document.getElementById('dishes-table-body');
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Cargando platos...</td></tr>';

    try {
        const dishes = await Dish.getAllDishes();
        console.log('Dishes loaded in main.js:', dishes);
        //Clear the table body
        tableBody.innerHTML = '';
        //Render each dish as a row in the table and if there are no dishes show a message
        if (dishes.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No hay platos disponibles.</td></tr>';
            return;
        }

        dishes.forEach(dish => {
            tableBody.innerHTML += dish.toHtmlRow();
        });

    } catch (error) {
        console.error('Error loading dishes:', error);
    }
}
//Function to load orders and render them
async function loadOrders() {
    const tableBody = document.getElementById('orders-table-body');
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Cargando pedidos...</td></tr>';

    try {
        allOrders = await Order.getAllOrders();
        console.log('Orders loaded in main.js:', allOrders);
        renderOrders(allOrders);
    } catch (error) {
        console.error('Error loading orders:', error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error: ${error.message}</td></tr>`;
    }
}

//Function to render orders of the array passed as parameter
function renderOrders(orders) {
    const tableBody = document.getElementById('orders-table-body');
    tableBody.innerHTML = '';
    
    if (orders.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No hay pedidos disponibles (o ninguno coincide con el filtro).</td></tr>';
        return;
    }

    orders.forEach(order => {
        tableBody.innerHTML += order.toHtmlRow(currentSettings.rate, currentSettings.symbol);
    });
}
//Function to load logs and render them
async function loadLogs() {
    const tableBody = document.getElementById('logs-table-body');
    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Cargando logs...</td></tr>';

    try {
        const logs = await Logs.getAllLogs();
        console.log('Logs loaded in main.js:', logs);
        tableBody.innerHTML = '';
        
        if (logs.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No hay logs disponibles.</td></tr>';
            return;
        }

        logs.forEach(log => {
            tableBody.innerHTML += log.toHtmlRow();
        });
    } catch (error) {
        console.error('Error loading logs:', error);
        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${error.message}</td></tr>`;
    }
}
//Function to setup the currency toggle
function setupCurrencyToggle() {
    const toggle = document.getElementById('currencyToggle');
    //Variable to cache the fetched USD rate
    let cachedUsdRate = null;
    //Set the initial state
    toggle.addEventListener('change', async (e) => {
        if (e.target.checked) {
            //Switch to USD
            if (!cachedUsdRate) {
                try {
                    const response = await fetch('https://api.frankfurter.app/latest?from=EUR&to=USD');
                    const data = await response.json();
                    cachedUsdRate = data.rates.USD;
                } catch (error) {
                    console.error('Error fetching exchange rate:', error);
                    alert('No se pudo obtener el tipo de cambio. Mostrando en EUR.');
                    e.target.checked = false;
                    return;
                }
            }
            currentSettings.rate = cachedUsdRate;
            currentSettings.symbol = '$';
        } else {
            //Revert to EUR
            currentSettings.rate = 1;
            currentSettings.symbol = '€';
        }

        //Re-render the orders with the new settings
        document.getElementById('filter-user-id').dispatchEvent(new Event('input'));
    });
}


//Setup navigation tabs
function setupNavigation() {
    //navs
    const buttons = document.querySelectorAll('.nav-spa-link');
    const sections = document.querySelectorAll('.admin-section');

    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            //Prevent the reload
            e.preventDefault();
            //Obtain the target section id from data attribute
            const targetId = e.target.getAttribute('data-target');

            //Hide all sections
            sections.forEach(section => { section.style.display = 'none'; });

            //Remove active class from all
            buttons.forEach(button => { button.classList.remove('active'); });
            //Add it to the active button
            e.target.classList.add('active');

            //Show the target section
            document.getElementById(targetId).style.display = 'block';

            //Load data if needed
            if (targetId === 'section-pedidos') {
                loadOrders();
            } else if (targetId === 'section-platos') {
                loadDishes();
            } else if (targetId === 'section-logs') {
                loadLogs();
            }
        })
    })

}
//Function to setup order filters
function setupOrderFilters() {
    const userIdInput = document.getElementById('filter-user-id');
    const orderedAtInput = document.getElementById('filter-ordered-at');
    const totalAmountInput = document.getElementById('filter-total-amount');

    const filterOrders = () => {
        const userIdVal = userIdInput.value.toLowerCase();
        const orderedAtVal = orderedAtInput.value.toLowerCase();
        const totalAmountVal = totalAmountInput.value.toLowerCase();

        const filtered = allOrders.filter(order => {
            const matchUser = order.user_id.toString().toLowerCase().includes(userIdVal);
            const matchDate = order.ordered_at.toLowerCase().includes(orderedAtVal);
            const matchAmount = order.total_amount.toString().toLowerCase().includes(totalAmountVal);
            return matchUser && matchDate && matchAmount;
        });

        renderOrders(filtered);
    };

    userIdInput.addEventListener('input', filterOrders);
    orderedAtInput.addEventListener('input', filterOrders);
    totalAmountInput.addEventListener('input', filterOrders);
}

//Function to setup modal listeners
function setupModalListeners() {
    //Take the delete dish confirmation button and add the listener
    const confirmDeleteBtn = document.getElementById('btnConfirmDelete');
    //Set the click listener and call the delete function from Dish class
    confirmDeleteBtn.addEventListener('click', async () => {
        try {
            const deleted = await Dish.delete(toDeleteDish);
            if (deleted) {
                alert('Plato eliminado con éxito');
                //Close the modal
                const modal = document.getElementById('modalDeleteDish');
                const dishModal = bootstrap.Modal.getInstance(modal);
                dishModal.hide();
                //Reload the dishes
                loadDishes();
            }
        } catch (error) {
            //Close the modal. To be implemented if there is an error..
            const modal = document.getElementById('modalDeleteDish');
            const dishModal = bootstrap.Modal.getInstance(modal);
            dishModal.hide();
            
            //Show the message in an alert
            alert('Error: ' + error.message);
        }
    })

    //Take the modal open event to clear previous data
    const createDishBtn = document.getElementById('btnCreateDish');
    createDishBtn.addEventListener('click', () => {
        document.getElementById('formDish').reset();
        document.getElementById('dish_id').value = '';
        document.getElementById('deleted_images').value = '[]';
        imagesToDelete = [];
        document.getElementById('modalDishTitle').innerHTML = 'Crear nuevo plato';
        document.getElementById('current-images-container').innerHTML = '<small class="text-muted">No tiene imágenes cargadas</small>';
    })

    //Take the dish form modal and add the listener to the submit event
    const saveDishForm = document.getElementById('formDish');
    saveDishForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        //Create a FormData object from the form
        const formData = new FormData(saveDishForm);
        //Chheck if the checkbox is set or not and set the value
        const isAvailable = document.getElementById('available').checked ? 1 : 0;
        formData.set('available', isAvailable);

        //check if we are editing or creating
        const dishId = formData.get('dish_id');
        try {
            if (dishId) {
                console.log('Editing dish with id: ', dishId);
                await Dish.update(formData);
            } else {
                console.log('Creating new dish');
                await Dish.create(formData);
            }
            //Close the modal
            const modal = document.getElementById('modalDishForm');
            const dishModal = bootstrap.Modal.getInstance(modal);
            dishModal.hide();
            loadDishes();
            alert('Plato guardado con éxito');

        } catch (error) {
            alert('Error guardando el plato: ' + error.message);
        }
    })

    //order modal listeners
    const confirmDeleteOrderBtn = document.getElementById('btnConfirmDeleteOrder');
    confirmDeleteOrderBtn.addEventListener('click', async () => {
        try {
            const deleted = await Order.delete(toDeleteOrder);
            if (deleted) {
                alert('Pedido eliminado con éxito');
                const modal = document.getElementById('modalDeleteOrder');
                const orderModal = bootstrap.Modal.getInstance(modal);
                orderModal.hide();
                loadOrders();
            }
        } catch (error) {
            const modal = document.getElementById('modalDeleteOrder');
            const orderModal = bootstrap.Modal.getInstance(modal);
            orderModal.hide();
            alert('Error: ' + error.message);
        }
    })
    
    const createOrderBtn = document.getElementById('btnCreateOrder');
    createOrderBtn.addEventListener('click', () => {
        document.getElementById('formOrder').reset();
        document.getElementById('order_id').value = '';
        document.getElementById('modalOrderTitle').innerHTML = 'Crear nuevo pedido';
        // Set default date to now
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('ordered_at').value = now.toISOString().slice(0, 16);
    })
    
    const saveOrderForm = document.getElementById('formOrder');
    saveOrderForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(saveOrderForm);
        const orderId = formData.get('order_id');

        //Construct the address object from the form fields
        const addrObj = [{
            zip: document.getElementById('addr_zip').value,
            city: document.getElementById('addr_city').value,
            street: document.getElementById('addr_street').value,
            country: document.getElementById('addr_country').value,
            province: document.getElementById('addr_province').value,
            apartment: document.getElementById('addr_apartment').value
        }];
        formData.set('delivery_addr', JSON.stringify(addrObj));
        //Try catch where checks if we are editing or creating. And call the respective function
        try {
            if (orderId) {
                await Order.update(formData);
            } else {
                await Order.create(formData);
            }
            const modal = document.getElementById('modalOrderForm');
            const orderModal = bootstrap.Modal.getInstance(modal);
            orderModal.hide();
            loadOrders();
            alert('Pedido guardado con éxito');
        } catch (error) {
            alert('Error guardando el pedido: ' + error.message);
        }
    })
}

//Function to expose the functions to the global scope. This is needed to call the handlers of the dish from the HTML
function setupGlobalFunctions() {
    //Global function to open the delete dish modal
    window.deleteDish = (id) => {
        toDeleteDish = id;
        document.getElementById('delete-dish-name').innerHTML = 'nº ' + id;
        const deleteModal = new bootstrap.Modal(document.getElementById('modalDeleteDish'));
        deleteModal.show();
    }
    //Global function to open the delete order modal
    window.deleteOrder = (id) => {
        toDeleteOrder = id;
        document.getElementById('delete-order-id').innerHTML = 'nº ' + id;
        const deleteModal = new bootstrap.Modal(document.getElementById('modalDeleteOrder'));
        deleteModal.show();
    }

    //Global function to handle image deletion when editing a dish
    window.markImageForDeletion = (imageName, imgContainerId) => {
        imagesToDelete.push(imageName); //push to the array
        //Update the hidden input value
        document.getElementById('deleted_images').value = JSON.stringify(imagesToDelete);

        //Remove the image from the dom
        const imgContainer = document.getElementById(imgContainerId);
        if (imgContainer) {
            imgContainer.remove();
        }
        if (document.getElementById('current-images-container').children.length === 0) {
            document.getElementById('current-images-container').innerHTML = '<small class="text-muted">No tiene imágenes cargadas</small>';
        }
    }

    //Global function to open the edit dish modal
    window.editDish = async (id) => {
        const toEditDish = id;
        //Take the modal element and open it
        const editModal = new bootstrap.Modal(document.getElementById('modalDishForm'));
        editModal.show();

        document.getElementById('modalDishTitle').innerHTML = 'Cargando datos...';

        //We fetch the data of the selected dish.
        const dish = await Dish.getById(toEditDish);
        //If the promise is fullfilled then we fill the form with de dish data
        if (dish) {
            imagesToDelete = [];
            document.getElementById('deleted_images').value = '[]';

            document.getElementById('modalDishTitle').innerHTML = 'Editar plato';
            document.getElementById('dish_id').value = dish.dish_id;
            document.getElementById('dish_name').value = dish.dish_name;
            document.getElementById('base_price').value = dish.base_price;
            document.getElementById('dish_description').value = dish.dish_description;
            document.getElementById('category').value = dish.category;
            
            //Map string topic to index for the select element
            const topicMap = { 'Mar': 0, 'Montaña': 1, 'Vegetariano': 2, 'Vegano': 3, 'Otros': 4 };
            document.getElementById('topic').value = topicMap[dish.topic] || dish.topic;
            
            document.getElementById('available').checked = (dish.available == 1);

            const imageContainer = document.getElementById('current-images-container')
            imageContainer.innerHTML = '';

            //Check if there are images array and show them
            let imagesArray = [];
            if (dish.images && Array.isArray(dish.images)) imagesArray = dish.images;
            if (imagesArray.length > 0) {
                imagesArray.forEach(img => {
                    //Create a unique id for the img container.
                    const imgContainerId = `img-container-${img.path}`;
                    const imageHtml = `
            <div class="position-relative d-inline-block me-2 mb-2" id="${imgContainerId}">
                <img src="${img.path}" 
                     alt="${img.alt}" 
                     class="img-thumbnail" 
                     style="width: 100px; height: 100px; object-fit: cover;">
                
                <button type="button" 
                        class="btn-close position-absolute top-0 end-0 bg-white p-1 shadow-sm" 
                        style="transform: translate(50%, -50%); width: 0.5rem; height: 0.5rem;"
                        onclick="markImageForDeletion('${img.path}', '${imgContainerId}')">
                </button>
            </div>
        `;
                    imageContainer.innerHTML += imageHtml;
                });
            } else {
                imageContainer.innerHTML = '<small class="text-muted">No tiene imágenes cargadas</small>';
            }

        }

    }
    //Global function to open the edit order modal
    window.editOrder = async (id) => {
        //Show modal and loading
        const editModal = new bootstrap.Modal(document.getElementById('modalOrderForm'));
        editModal.show();
        document.getElementById('modalOrderTitle').innerHTML = 'Cargando datos...';
        document.getElementById('formOrder').reset(); // Reset form first

        //Fetch the order data
        const order = await Order.getById(id);
        if (order) {
            document.getElementById('modalOrderTitle').innerHTML = 'Editar Pedido';
            document.getElementById('order_id').value = order.order_id;
            document.getElementById('user_id').value = order.user_id;
            document.getElementById('order_status').value = order.order_status;
            document.getElementById('total_amount').value = order.total_amount;
            const dateStr = order.ordered_at.replace(' ', 'T').slice(0, 16);
            document.getElementById('ordered_at').value = dateStr;
            document.getElementById('discount_id').value = order.discount_id || '';
            document.getElementById('notes').value = order.notes || '';

            //Parse the delivery_addr JSON and fill the address fields
            try {
                let parsedAddr = JSON.parse(order.delivery_addr);
                //Parse as array or object
                let addrObj = {};
                if (Array.isArray(parsedAddr) && parsedAddr.length > 0) {
                    addrObj = parsedAddr[0];
                } else if (typeof parsedAddr === 'object' && parsedAddr !== null) {
                    addrObj = parsedAddr;
                }
                
                document.getElementById('addr_street').value = addrObj.street || '';
                document.getElementById('addr_apartment').value = addrObj.apartment || '';
                document.getElementById('addr_city').value = addrObj.city || '';
                document.getElementById('addr_province').value = addrObj.province || '';
                document.getElementById('addr_zip').value = addrObj.zip || '';
                document.getElementById('addr_country').value = addrObj.country || '';

            } catch(e){
                console.warn('Could not parse delivery_addr JSON, falling back to raw string in street field', e);
                document.getElementById('addr_street').value = order.delivery_addr;
            }
        }
    }
}
