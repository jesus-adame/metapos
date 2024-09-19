import axios from "axios";
import { ref } from 'vue';

export default class QuoteService {
    constructor() {
        this.quotes = ref([]);
    }

    getPurchases() {
        return this.quotes;
    }

    async findByCode(code) {
        const url = '/api/quotes/search';
        const response = await axios.post(url, { code: code });
        this.quotes.value = response.data

        return response
    }

    async paginate(params) {
        const url = route('api.quotes.index');
        const response = await axios.get(url, { params });
        this.quotes.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.quotes.index');
        const response = await axios.get(url, { params: { page: 1, rows: 100 } });
        this.quotes.value = response.data

        return response
    }

    async deleteItem(url) {
        const response = axios.post(url, { _method: 'delete' })

        return response
    }
}