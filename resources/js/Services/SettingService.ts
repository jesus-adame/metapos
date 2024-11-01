import axios from "axios";
import { ref } from 'vue';

export default class SettingService {
    private settings

    constructor() {
        this.settings = ref([]);
    }

    getSettings() {
        return this.settings;
    }

    async findByCode(code: any) {
        const url = '/api/settings/search';
        const response = await axios.post(url, { code: code });
        this.settings.value = response.data

        return response
    }

    async paginate(page: any, rows: any) {
        const url = `/api/settings`;
        const response = await axios.get(url, { params: { page, rows: rows } });
        this.settings.value = response.data

        return response
    }

    async fetchAll() {
        const url = '/api/settings';
        const response = await axios.get(url);
        this.settings.value = response.data

        return response
    }
}