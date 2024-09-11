import axios from "axios";
import { ref } from 'vue';

export default class CustomerService {
    constructor() {
        this.categories = ref([]);
    }

    getCustomers() {
        return this.categories;
    }

    async findByCode(code) {
        const url = '/api/categories/search';
        const response = await axios.post(url, { code: code });
        this.categories.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = route('api.categories.index');
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.categories.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.categories.index');
        const response = await axios.get(url);
        this.categories.value = response.data

        return response
    }
}