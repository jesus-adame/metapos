import axios from "axios";
import { ref } from 'vue';

export default class LocationService {
    constructor() {
        this.locations = ref([]);
    }

    getLocations() {
        return this.locations;
    }

    async findByCode(code) {
        const url = '/api/locations/search';
        const response = await axios.post(url, { code: code });
        this.locations.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = `/api/locations`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.locations.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/locations';
        const response = await axios.get(url);
        this.locations.value = response.data

        return response
    }
}