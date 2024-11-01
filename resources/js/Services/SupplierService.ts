import axios from "axios";
import { ref } from 'vue';

export default class SupplierService {
    private suppliers;

    constructor() {
        this.suppliers = ref([]);
    }

    getSuppliers() {
        return this.suppliers;
    }

    async findByCode(code: any) {
        const url = '/api/suppliers/search';
        const response = await axios.post(url, { code: code });
        this.suppliers.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = `/api/suppliers`;
        const response = await axios.get(url, { params: { page, rows: rows } });
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