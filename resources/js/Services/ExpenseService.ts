import axios from "axios";
import { ref } from 'vue';

export default class ExpenseService {
    private expenses;

    constructor() {
        this.expenses = ref([]);
    }

    getExpenses() {
        return this.expenses;
    }

    async findByCode(code: any) {
        const url = '/api/expenses/search';
        const response = await axios.post(url, { code: code });
        this.expenses.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = `/api/expenses`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.expenses.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/expenses';
        const response = await axios.get(url);
        this.expenses.value = response.data

        return response
    }
}