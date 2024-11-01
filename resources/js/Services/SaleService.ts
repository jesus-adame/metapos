import axios from "axios";
import { ref } from 'vue';

export default class SaleService {
    private sales;

    constructor() {
        this.sales = ref([]);
    }

    getPurchases() {
        return this.sales;
    }

    async findByCode(code: any) {
        const url = '/api/sales/search';
        const response = await axios.post(url, { code: code });
        this.sales.value = response.data

        return response
    }

    async paginate(params: any) {
        const url = route('api.sales.index');
        const response = await axios.get(url, { params });
        this.sales.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.sales.index');
        const response = await axios.get(url, { params: { page: 1, rows: 100 } });
        this.sales.value = response.data

        return response
    }

    async deleteItem(url: any) {
        const response = axios.post(url, { _method: 'delete' })

        return response
    }
}