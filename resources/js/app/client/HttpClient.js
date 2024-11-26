export default class HttpClient {
    client = null;
    
    constructor(baseUrl, token = null) {
        if (! this.client) {
            this.client = axios.create({
                baseURL: baseUrl,
            });
        }

        if (token) {
            this.client.defaults.headers.common.Authorization = `Bearer ${token}`;
        }
    }

    changeToken(token = null) {
        if (token) {
            this.client.defaults.headers.common.Authorization = `Bearer ${token}`;
            return;
        }

        this.client.defaults.headers.common.Authorization = null;
    }
    
    async get(url, config = {}) {
        try {
            const response = await this.client.get(url, config);
            return response.data;
        } catch (error) {
            console.error('Error fetching data:', error);
            throw new Error(
                error?.response?.data?.message ?? 'Unknown error'
            );
        }
    }
    
    async post(url, data, config = {}) {
        try {
            const response = await this.client.post(url, data, config);
            return response.data;
        } catch (error) {
            console.error('Error sending data:', error);
            throw new Error(
                error?.response?.data?.message ?? 'Unknown error'
            );
        }
    }
    
    async put(url, data, config = {}) {
        try {
            const response = await this.client.put(url, data, config);
            return response.data;
        } catch (error) {
            console.error('Error updating data:', error);
            throw new Error(
                error?.response?.data?.message ?? 'Unknown error'
            );
        }
    }
    
    
    async patch(url, data, config = {}) {
        try {
            const response = await this.client.patch(url, data, config);
            return response.data;
        } catch (error) {
            console.error('Error updating data:', error);
            throw new Error(
                error?.response?.data?.message ?? 'Unknown error'
            );
        }
    }
    
    async delete(url, config = {}) {
        try {
            const response = await this.client.delete(url, config);
            return response.data;
        } catch (error) {
            console.error('Error deleting data:', error);
            throw new Error(
                error?.response?.data?.message ?? 'Unknown error'
            );
        }
    }
}