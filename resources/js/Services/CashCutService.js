import axios from "axios";
import { ref } from 'vue';

export default class CashCutService {
    constructor() {
        this.cashCuts = ref([]);
    }

    getCashCuts() {
        return this.cashCuts;
    }

    async findByCode(code) {
        const url = '/api/cash-cuts/search';
        const response = await axios.post(url, { code: code });
        this.cashCuts.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = `/api/cash-cuts`;
        const response = await axios.get(url, { params: { page: page, rows: rows } });
        this.cashCuts.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/cash-cuts';
        const response = await axios.get(url);
        this.cashCuts.value = response.data

        return response
    }
}