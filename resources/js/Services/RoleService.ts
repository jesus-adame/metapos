import axios from "axios";
import { ref } from 'vue';

export default class RoleService {
    private roles;

    constructor() {
        this.roles = ref([]);
    }

    getUsers() {
        return this.roles;
    }

    async findByCode(code: any) {
        const url = '/api/roles/search';
        const response = await axios.post(url, { code: code });
        this.roles.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = `/api/roles`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.roles.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/roles';
        const response = await axios.get(url);
        this.roles.value = response.data

        return response
    }
}