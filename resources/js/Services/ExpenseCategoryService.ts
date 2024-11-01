import axios from "axios";
import { ref } from 'vue';

export default class ExpenseCategoryService {
    private expense_categories;

    constructor() {
        this.expense_categories = ref([]);
    }

    getCategories() {
        return this.expense_categories;
    }

    async findByCode(code: any) {
        const url = '/api/expense_categories/search';
        const response = await axios.post(url, { code: code });
        this.expense_categories.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = route('api.expense_categories.index');
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.expense_categories.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.expense_categories.index');
        const response = await axios.get(url);
        this.expense_categories.value = response.data

        return response
    }
}