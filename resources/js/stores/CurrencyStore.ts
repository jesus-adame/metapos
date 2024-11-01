import { Currency } from "@/types"
import { AxiosResponse } from "axios"
import { defineStore } from "pinia"
import CurrencyService from "@/Services/CurrencyService";

const currencyService = new CurrencyService()

interface CurrencyState {
    rows: number,
    page: number,
    selected: Currency | null,
    currencies: Currency[],
    totalRecords: number,
}

export const useCurrencyStore = defineStore('currency', {
    state: (): CurrencyState => ({
        rows: 10,
        page: 1,
        selected: null,
        currencies: [],
        totalRecords: 0,
    }),
    getters: {
        getCurrencies: (state) => state.currencies
    },
    actions: {
        pushItem(currency: Currency) {
            this.currencies.push(currency)
        },

        setCurrency(selected: Currency) {
            this.selected = { ...selected }
        },

        setCurrencies(currencies: Currency[]) {
            this.currencies = [...currencies]
        },

        fetchItems() {
            currencyService.paginate(this.page, this.rows)
                .then((response: AxiosResponse) => {
                    const pagination = response.data
                    this.currencies = [...pagination.data]
                    this.totalRecords = pagination.total
                })
        },
    },
})