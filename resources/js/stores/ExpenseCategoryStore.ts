import { ExpenseCategory } from "@/types"
import { AxiosResponse } from "axios"
import { defineStore } from "pinia"
import ExpenseCategoryService from "@/Services/ExpenseCategoryService";

const categoryService = new ExpenseCategoryService()

interface ExpenseCategoryState {
    selected: ExpenseCategory | null,
    categories: ExpenseCategory[],
    totalRecords: number,
    totalCategories: number,
}

export const useExpenseCategoryStore = defineStore('expense_category', {
    state: (): ExpenseCategoryState => ({
        selected: null,
        categories: [],
        totalRecords: 0,
        totalCategories: 0,
    }),
    getters: {
        getCategories: (state) => state.categories
    },
    actions: {
        pushItem(category: ExpenseCategory) {
            this.categories.push(category)
        },

        setCategory(selected: ExpenseCategory) {
            this.selected = { ...selected }
        },

        setCategories(categories: ExpenseCategory[]) {
            this.categories = [...categories]
        },

        fetchItems() {
            categoryService.paginate()
                .then((response: AxiosResponse) => {
                    const pagination = response.data
                    this.categories = [...pagination.data]
                    this.totalRecords = pagination.total
                    this.totalCategories = pagination.totalCategories
                })
        },
    },
})