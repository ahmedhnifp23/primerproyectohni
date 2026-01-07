import { buildApiUrl } from '../config.js';

export class Logs {

    static endpoint = 'logs';

    constructor(log_id, user_id, action, table_name, done_at) {
        this.log_id = log_id;
        this.user_id = user_id;
        this.action = action;
        this.table_name = table_name;
        this.done_at = done_at;
    }


    /**
     * Static async method to get all the logs.
     * Returns a promise that resolves to an array of logs objects or an empty array if there are no logs.
     * @returns {Promise<Logs[]>}
     */
    static async getAllLogs() {
        try {
            const url = buildApiUrl(this.endpoint);
            console.log('Fetching logs from URL:', url);
            const response = await fetch(url);

            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.data || `HTTP error: ${response.status}`);
            }
            const json = await response.json();
            
            if (json.data && Array.isArray(json.data)) {
                return json.data.map(log => new Logs(
                    log.log_id,
                    log.user_id,
                    log.action,
                    log.table_name,
                    log.done_at
                ));
            }
            return [];

        } catch (error) {
            console.error('Error fetching logs:', error);
            throw error;
        }

    }

    //Return HTML table row of the log
    toHtmlRow() {
        let badgeClass = 'bg-secondary';
        if (this.action === 'Create') badgeClass = 'bg-success';
        if (this.action === 'Update') badgeClass = 'bg-warning text-dark';
        if (this.action === 'Delete') badgeClass = 'bg-danger';

        return `
        <tr>
            <td>${this.log_id}</td>
            <td>User #${this.user_id}</td>
            <td><span class="badge ${badgeClass}">${this.action}</span></td>
            <td>${this.table_name}</td>
            <td>${this.done_at}</td>
        </tr>
    `;
    }

}
