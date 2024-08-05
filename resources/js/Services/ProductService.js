import axios from "axios";
import { ref } from 'vue';

export default class ProductService {
    constructor() {
        this.products = ref([]);
    }

    getProducts() {
        return this.products;
    }

    async findByCode(code) {
        const url = '/api/products/search';
        const response = await axios.post(url, { code: code });
        this.products.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = `/api/products`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.products.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/products';
        const response = await axios.get(url, { params: { page: 1, rows: 100 } });
        this.products.value = response.data

        return response
    }
}