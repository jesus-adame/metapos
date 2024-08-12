import axios from "axios";
import { ref } from 'vue';

export default class SaleService {
    constructor() {
        this.sales = ref([]);
    }

    getPurchases() {
        return this.sales;
    }

    async findByCode(code) {
        const url = '/api/sales/search';
        const response = await axios.post(url, { code: code });
        this.sales.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = route('api.sales.index');
        const response = await axios.get(url, { params: { page, rows } });
        this.sales.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.sales.index');
        const response = await axios.get(url, { params: { page: 1, rows: 100 } });
        this.sales.value = response.data

        return response
    }
}