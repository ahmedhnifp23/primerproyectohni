//import of the base API url
import { buildApiUrl, Config } from '../config.js';

export class Dish {

    static endpoint = 'dish';

    constructor(dish_id, dish_name, dish_description, topic, base_price, images, available, category) {
        this.dish_id = dish_id;
        this.dish_name = dish_name;
        this.dish_description = dish_description;
        this.topic = topic;
        this.base_price = base_price;
        this.images = images;
        this.available = available;
        this.category = category;
    }


    /**
     * Static async method to get all the dishes.
     * Returns a promise that resolves to an array of Dish objects or an empty array if there are no dishes.
     * @returns {Promise<Dish[]>}
     */
    static async getAllDishes() {
        try {
            const url = buildApiUrl('dish');
            //debug
            console.log('Fetching dishes from URL:', url);
            //Wait for the response of the fetch call
            const response = await fetch(url);

            //If the response is not ok, throw an error
            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
            //Parse the response as json
            const json = await response.json();
            //Map the json to an array of dish objects
            if (json.data && Array.isArray(json.data)) {
                return json.data.map(dish => new Dish(
                    dish.dish_id,
                    dish.dish_name,
                    dish.dish_description,
                    dish.topic,
                    dish.base_price,
                    dish.images,
                    dish.available,
                    dish.category
                ));
            }
            return [];

        } catch (error) {
            console.error('Error fetching dishes:', error);
            throw error;
        }

    }

    //Return HTML table row of the dish
    toHtmlRow() {
        return `
        <tr>
            <td>${this.dish_id}</td>
            <td><strong>${this.dish_name}</strong></td>
            <td>${this.topic}</td>
            <td>${this.category}</td>
            <td>${this.available == 1 ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>'}</td>
            <td class="text-end">${parseFloat(this.base_price).toFixed(2)} €</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" onclick="editDish(${this.dish_id})"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm" onclick="deleteDish(${this.dish_id})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `;
    }

    //Function to get a dish by id
    static async getById(id){
        try{
            const url = buildApiUrl(this.endpoint, { dish_id: id });
            const response = await fetch(url);
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }

            //Parse to json
            const json = await response.json();
            console.log('Fetched dish data:', json);
            if(json.data){
                const dish = new Dish(
                    json.data.dish_id,
                    json.data.dish_name,
                    json.data.dish_description,
                    json.data.topic,
                    json.data.base_price,
                    json.data.images,
                    json.data.available,
                    json.data.category
                );
                return dish;
            }

        } catch(error){
            console.error('Error fetching dish by id:', error);
            throw error;
        }
    }

    //Function to delete a dish
    static async delete(id){
        const url = buildApiUrl(this.endpoint, {dish_id: id});
        try{
            const response = await fetch(url,{ method: 'DELETE' });
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
        } catch(error){
            console.error('Error deleting dish:', error);
            throw error;
        }
        return true;
    }
    /** Function to update a dish
     * @returns {Promise<boolean>} true if the dish was updated successfully false other
     */
    static async update(formData){
        console.log('Updating dish:', formData);
        const url = buildApiUrl(this.endpoint);
        try{
            const response = await fetch(url,{ method: 'POST', body: formData });
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
        } catch(error){
            console.error('Error updating dish:', error);
            throw error;
        }
        return true;
    }

    /** Function to create a dish
     * @returns {Promise<boolean>} true if the dish was created successfully false other
     */
    static async create(formData){
        console.log('Creating dish:', formData);
        const url = buildApiUrl(this.endpoint);
        try{
            const response = await fetch(url,{ method: 'POST', body: formData });
            if(!response.ok){
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
        } catch(error){
            console.error('Error creating dish:', error);
            throw error;
        }
        return true;
    }









}