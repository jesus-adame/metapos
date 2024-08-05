import axios from "axios";
import { ref } from 'vue';

export default class InventoryTransactionService {
    constructor() {
        this.transactions = ref([]);
    }

    getTransactions() {
        return this.transactions;
    }

    async findByCode(code) {
        const url = '/api/inventory-transactions/search';
        const response = await axios.post(url, { code: code });
        this.transactions.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = `/api/inventory-transactions`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.transactions.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/inventory-transactions';
        const response = await axios.get(url);
        this.transactions.value = response.data

        return response
    }
}