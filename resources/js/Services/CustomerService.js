import axios from "axios";
import { ref } from 'vue';

export default class CustomerService {
    constructor() {
        this.customers = ref([]);
    }

    getCustomers() {
        return this.customers;
    }

    async findByCode(code) {
        const url = '/api/customers/search';
        const response = await axios.post(url, { code: code });
        this.customers.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/customers';
        const response = await axios.get(url);
        this.customers.value = response.data

        return response
    }
}