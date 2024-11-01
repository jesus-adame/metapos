import axios from "axios";
import { ref } from 'vue';

export default class UserService {
    private users;

    constructor() {
        this.users = ref([]);
    }

    getUsers() {
        return this.users;
    }

    async findByCode(code: any) {
        const url = '/api/users/search';
        const response = await axios.post(url, { code: code });
        this.users.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = `/api/users`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.users.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/users';
        const response = await axios.get(url);
        this.users.value = response.data

        return response
    }
}