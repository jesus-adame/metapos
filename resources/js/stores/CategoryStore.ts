import { Category } from "@/types"
import { defineStore } from "pinia"

interface CategoryState {
    selected: Category | null,
    categories: Category[],
}

export const useCategoryStore = defineStore('category', {
    state: (): CategoryState => ({
        selected: null,
        categories: [],
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
    },
})