import axios from "axios";
import { ref } from 'vue';

export default class CurrencyService {
    private currencies;

    constructor() {
        this.currencies = ref([]);
    }

    getExpenses() {
        return this.currencies;
    }

    async findByCode(code: any) {
        const url = '/api/currencies/search';
        const response = await axios.post(url, { code: code });
        this.currencies.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = route('api.currencies.index');
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.currencies.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.currencies.index');
        const response = await axios.get(url);
        this.currencies.value = response.data

        return response
    }
}