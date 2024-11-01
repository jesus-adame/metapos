import axios from "axios";
import { ref } from 'vue';

export default class CashCutService {
    private cashCuts;

    constructor() {
        this.cashCuts = ref([]);
    }

    getCashCuts() {
        return this.cashCuts;
    }

    async findByCode(code: any) {
        const url = '/api/cash-cuts/search';
        const response = await axios.post(url, { code: code });
        this.cashCuts.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
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