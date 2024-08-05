import axios from "axios";
import { ref } from 'vue';

export default class BranchService {
    constructor() {
        this.branches = ref([]);
    }

    getBranches() {
        return this.branches;
    }

    async findByCode(code) {
        const url = '/api/branches/search';
        const response = await axios.post(url, { code: code });
        this.branches.value = response.data

        return response
    }

    async paginate(page, rows) {
        const url = `/api/branches`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.branches.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/branches';
        const response = await axios.get(url);
        this.branches.value = response.data

        return response
    }
}