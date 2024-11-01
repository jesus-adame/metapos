import axios from "axios";
import { ref } from 'vue';

export default class CashRegisterService {
    private cashRegisters;

    constructor() {
        this.cashRegisters = ref([]);
    }

    getCashRegisters() {
        return this.cashRegisters;
    }

    async search(params: any) {
        const url = '/api/cash-registers/search';
        const response = await axios.post(url, { params: params });
        this.cashRegisters.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = `/api/cash-registers`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.cashRegisters.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/cash-registers';
        const response = await axios.get(url);
        this.cashRegisters.value = response.data

        return response
    }
}