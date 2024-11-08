import { Category } from "@/types"
import { AxiosResponse } from "axios"
import { defineStore } from "pinia"
import CategoryService from "@/Services/CategoryService";

const categoryService = new CategoryService()

interface CategoryState {
    rows: number,
    page: number,
    selected: Category | null,
    categories: Category[],
    totalRecords: number,
    totalCategories: number,
}

export const useCategoryStore = defineStore('category', {
    state: (): CategoryState => ({
        rows: 10,
        page: 1,
        selected: null,
        categories: [],
        totalRecords: 0,
        totalCategories: 0,
    }),
    getters: {
        getCategories: (state) => state.categories
    },
    actions: {
        pushItem(category: Category) {
            this.categories.push(category)
        },

        setCategory(selected: Category) {
            this.selected = { ...selected }
        },

        setCategories(categories: Category[]) {
            this.categories = [...categories]
        },

        fetchItems() {
            categoryService.paginate(this.page, this.rows)
                .then((response: AxiosResponse) => {
                    const pagination = response.data
                    this.categories = [...pagination.data]
                    this.totalRecords = pagination.total
                    this.totalCategories = pagination.totalSales
                })
        },
    },
})