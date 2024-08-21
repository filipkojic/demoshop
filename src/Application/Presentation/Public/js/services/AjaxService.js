/**
 * Class for sending AJAX requests.
 */
class AjaxService {
    /**
     * Send an AJAX GET request to the specified URL.
     *
     * @param {string} url - The URL to send the request to.
     * @returns {Promise<object>} - A promise that resolves to the response JSON.
     */
    static async get(url) {
        const response = await fetch(url);
        return response.json();
    }

    /**
     * Send an AJAX POST request to the specified URL with the given JSON string data.
     *
     * @param {string} url - The URL to send the request to.
     * @param {string} data - The JSON string data to be sent in the body of the request.
     * @returns {Promise<object>} - A promise that resolves to the response JSON.
     */
    static async post(url, data) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: data
        });
        return response.json();
    }

    /**
     * Send an AJAX DELETE request to the specified URL with optional JSON string data.
     *
     * @param {string} url - The URL to send the request to.
     * @param {string} [data] - The optional JSON string data to send with the request.
     * @returns {Promise<object>} - A promise that resolves to the response JSON.
     */
    static async delete(url, data = null) {
        const config = {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        };
        if (data) {
            config.body = data;
        }
        const response = await fetch(url, config);
        return response.json();
    }

    /**
     * Send an AJAX PUT request to the specified URL with the given JSON string data.
     *
     * @param {string} url - The URL to send the request to.
     * @param {string} data - The JSON string data to be sent in the body of the request.
     * @returns {Promise<object>} - A promise that resolves to the response JSON.
     */
    static async put(url, data) {
        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: data
        });
        return response.json();
    }

    /**
     * Send an AJAX PATCH request to the specified URL with the given JSON string data.
     *
     * @param {string} url - The URL to send the request to.
     * @param {string} data - The JSON string data to be sent in the body of the request.
     * @returns {Promise<object>} - A promise that resolves to the response JSON.
     */
    static async patch(url, data) {
        const response = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: data
        });
        return response.json();
    }
}
