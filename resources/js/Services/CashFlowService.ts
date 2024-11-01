import axios from "axios";
import { ref } from 'vue';

export default class CashFlowService {
    private cashFlows

    constructor() {
        this.cashFlows = ref([]);
    }

    getCashFlows() {
        return this.cashFlows;
    }

    async findByCode(code: any) {
        const url = '/api/cash-flows/search';
        const response = await axios.post(url, { code: code });
        this.cashFlows.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = `/api/cash-flows`;
        const response = await axios.get(url, { params: { page: page, rows: rows } });
        this.cashFlows.value = response.data

        return response
    }

    async resume() {
        const url = route('api.cash-flows.resume');
        const response = await axios.get(url);
        this.cashFlows.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/cash-flows';
        const response = await axios.get(url);
        this.cashFlows.value = response.data

        return response
    }
}