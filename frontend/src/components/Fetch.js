import axios from 'axios'

class fetchUrl {

    static async get(url,body) {
        try {
            const response = await axios.get(url,body)
            console.log("In fetch: ",url)
            if (response.status >= 200 && response.status < 300) {
                return response.data;
            } else {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
        } catch (error) {
            throw new Error(`Fetch error: ${error.message}`);
        }
    }

    static async post(url,body) {
        try {
            const response = await axios.post(url,body)
            console.log("In POST fetch: ",url)

            if (response.status >= 200 && response.status < 300) {
                return response.data;
            } else {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
        } catch (error) {
            throw new Error(`Fetch error: ${error.message}`);
        }
    }

    static async put(url,body) {
        try {
            const response = await axios.put(url,body)
            console.log("In PUT fetch: ",url)
            if (response.status >= 200 && response.status < 300) {
                return response.data;
            } else {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
        } catch (error) {
            throw new Error(`Fetch error: ${error.message}`);
        }
    }

    static async delete(url,body) {
        try {
            await axios.delete(url,body)
            console.log("In DELETE fetch: ",url)
        } catch (error) {
            console.log("In DELETE fetch: ",error.message)
        }
    }
}
export default fetchUrl;