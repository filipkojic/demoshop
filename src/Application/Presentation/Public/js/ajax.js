/**
 * Send an AJAX GET request to the specified URL.
 *
 * @param {string} url - The URL to send the request to.
 * @returns {Promise<object>} - A promise that resolves to the response JSON.
 */
async function ajaxGet(url) {
    const response = await fetch(url);
    return response.json();
}

/**
 * Send an AJAX POST request to the specified URL with the given data.
 *
 * @param {string} url - The URL to send the request to.
 * @param {string} data - The data to be sent in the body of the request.
 * @returns {Promise<object>} - A promise that resolves to the response JSON.
 */
async function ajaxPost(url, data) {
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
 * Send an AJAX DELETE request to the specified URL.
 *
 * @param {string} url - The URL to send the request to.
 * @returns {Promise<object>} - A promise that resolves to the response JSON.
 */
async function ajaxDelete(url) {
    const response = await fetch(url, {
        method: 'DELETE'
    });
    return response.json();
}

/**
 * Send an AJAX PUT request to the specified URL with the given data.
 *
 * @param {string} url - The URL to send the request to.
 * @param {object} data - The data to be sent in the body of the request.
 * @returns {Promise<object>} - A promise that resolves to the response JSON.
 */
async function ajaxPut(url, data) {
    const response = await fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    return response.json();
}

/**
 * Send an AJAX PATCH request to the specified URL with the given data.
 *
 * @param {string} url - The URL to send the request to.
 * @param {object} data - The data to be sent in the body of the request.
 * @returns {Promise<object>} - A promise that resolves to the response JSON.
 */
async function ajaxPatch(url, data) {
    const response = await fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    return response.json();
}
