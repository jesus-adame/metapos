import axios from "axios";
import { ref } from 'vue';

export default class CurrencyService {
    constructor() {
        this.currencies = ref([]);
    }

    getExpenses() {
        return this.currencies;
    }

    async findByCode(code) {
        const url = '/api/currencies/search';
        const response = await axios.post(url, { code: code });
        this.currencies.value = response.data

        return response
    }

    async paginate(page, rows) {
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