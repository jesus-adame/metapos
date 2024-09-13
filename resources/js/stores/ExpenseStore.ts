import { Expense } from "@/types"
import { AxiosResponse } from "axios"
import { defineStore } from "pinia"
import ExpenseService from "@/Services/ExpenseService";

const expenseService = new ExpenseService()

interface ExpenseState {
    selected: Expense | null,
    expenses: Expense[],
    totalRecords: number,
    totalExpenses: number,
}

export const useExpenseStore = defineStore('expense', {
    state: (): ExpenseState => ({
        selected: null,
        expenses: [],
        totalRecords: 0,
        totalExpenses: 0,
    }),
    getters: {
        getExpenses: (state) => state.expenses
    },
    actions: {
        pushItem(expense: Expense) {
            this.expenses.push(expense)
        },

        setCategory(selected: Expense) {
            this.selected = { ...selected }
        },

        setExpenses(expenses: Expense[]) {
            this.expenses = [...expenses]
        },

        fetchItems() {
            expenseService.paginate()
                .then((response: AxiosResponse) => {
                    const pagination = response.data
                    this.expenses = [...pagination.data]
                    this.totalRecords = pagination.total
                    this.totalExpenses = pagination.totalExpenses
                })
        },
    },
})