import axios from "axios";
import { ref } from 'vue';

export default class ProductService {
    private products;

    constructor() {
        this.products = ref([]);
    }

    getProducts() {
        return this.products;
    }

    async findByCode(code: string) {
        const url = route('api.products.search');
        const response = await axios.post(url, { code: code });
        this.products.value = response.data

        return response
    }

    async findByCategory(category: string) {
        const url = route('api.products.category');
        const response = await axios.post(url, { category: category });
        this.products.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = route('api.products.index');
        const response = await axios.get(url, { params: { page: page, rows: rows } });
        this.products.value = response.data

        return response
    }

    async fetchAll() {
        const url = route('api.products.index');
        const response = await axios.get(url, { params: { page: 1, rows: 50 } });
        this.products.value = response.data

        return response
    }
}