import axios from "axios";
import { ref } from 'vue';

export default class SupplierService {
    constructor() {
        this.suppliers = ref([]);
    }

    getSuppliers() {
        return this.suppliers;
    }

    async findByCode(code) {
        const url = '/api/suppliers/search';
        const response = await axios.post(url, { code: code });
        this.suppliers.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/suppliers';
        const response = await axios.get(url);
        this.suppliers.value = response.data

        return response
    }
}