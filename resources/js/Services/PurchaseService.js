import axios from "axios";
import { ref } from 'vue';

export default class PurchaseService {
    constructor() {
        this.purchases = ref([]);
    }

    getPurchases() {
        return this.purchases;
    }

    async findByCode(code) {
        const url = '/api/purchases/search';
        const response = await axios.post(url, { code: code });
        this.purchases.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = route('api.purchases.index');
        const response = await axios.get(url, { params: { page: page, rows: rows } });
        this.purchases.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.purchases.index');
        const response = await axios.get(url, { params: { page: 1, rows: 100 } });
        this.purchases.value = response.data

        return response
    }
}