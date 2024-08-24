import { CashRegister, Location, User } from "@/types"
import { usePage } from "@inertiajs/vue3"
import { defineStore } from "pinia"

const page = usePage()

interface AuthState {
    user: User | null,
    cashRegister: CashRegister | null,
    location: Location | null,
    locations: Location[],
    csrf_token: string | null,
}

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => ({
        user: page.props.auth.user,
        cashRegister: page.props.cashRegister,
        location: page.props.location,
        locations: page.props.locations,
        csrf_token: page.props.csrf_token
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