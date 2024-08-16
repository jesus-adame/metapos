import { CashRegister, Location, User } from "@/types"
import { usePage } from "@inertiajs/vue3"
import { defineStore } from "pinia"

const page = usePage()

interface State {
    user: User | null,
    cashRegister: CashRegister | null,
    location: Location | null
}

export const useAuthStore = defineStore('auth', {
    state: (): State => ({
        user: page.props.auth.user,
        cashRegister: page.props.cashRegister,
        location: page.props.location,
    }),
    getters: {
        getUser: (state) => state.user
    },
    actions: {
        setUser(user: User) {
            this.user = { ...user }
        },

        setCashRegister(cashRegister: CashRegister) {
            this.cashRegister = { ...cashRegister }
        },

        setLocation(location: Location) {
            this.location = { ...location }
        }
    },
})