import axios from 'axios'

class fetchUrl {

    static async get(url,body) {
        try {
            const response = await axios.get(url,body)
            console.log("In fetch: ",url)
                return response.data;
        } catch (error) {
            const errorMessage = error.response.data.message;
            throw new Error(`${errorMessage}`);
        }
    }

    static async post(url,body) {
        try {
            const response = await axios.post(url,body)
            console.log("In POST fetch: ",url)
                return response.data;
        } catch (error) {
            const errorMessage = error.response.data.message;
            throw new Error(`${errorMessage}`);
        }
    }

    static async put(url,body) {
        try {
            const response = await axios.put(url,body)
            console.log("In PUT fetch: ",url)
                return response.data;
        } catch (error) {
            const errorMessage = error.response.data.message;
            throw new Error(`${errorMessage}`);
        }
    }

    static async delete(url,body) {
        try {
            const response = await axios.delete(url,body)
            console.log("In DELETE fetch: ",url)
            return response;
        } catch (error) {
            const errorMessage = error.response.data.message;
            throw new Error(`${errorMessage}`);
        }
    }
}
export default fetchUrl;