import { Customer } from "@/types"
import { defineStore } from "pinia"

interface CustomerState {
    selected: Customer | null,
    customers: Customer[],
}

export const useCustomerStore = defineStore('customer', {
    state: (): CustomerState => ({
        selected: null,
        customers: [],
    }),
    getters: {
        getCustomers: (state) => state.customers
    },
    actions: {
        pushItem(customer: Customer) {
            this.customers.push(customer)
        },

        setCustomer(selected: Customer) {
            this.selected = { ...selected }
        },

        setCustomers(customers: Customer[]) {
            this.customers = [...customers]
        },
    },
})