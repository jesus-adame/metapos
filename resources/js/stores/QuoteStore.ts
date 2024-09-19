import QuoteService from "@/Services/QuoteService"
import { CashRegister, Quote } from "@/types"
import { AxiosResponse } from "axios"
import { defineStore } from "pinia"
import { DataTablePageEvent } from "primevue/datatable"

interface QuoteState {
    sales: Quote[],
    totalRecords: number,
    rows: number,
    page: number,
    dates: any[],
    status: string | null,
    statusList: any[],
}

const quoteService = new QuoteService()

export const useQuoteStore = defineStore('quote', {
    state: (): QuoteState => ({
        sales: [],
        totalRecords: 0,
        rows: 10,
        page: 1,
        dates: [],
        status: null,
        statusList: [
            { label: 'Pendiente', value: 'pending' },
            { label: 'Pagada', value: 'completed' },
            { label: 'Cancelada', value: 'canceled' },
        ]
    }),
    actions: {
        setStatus(status: string | null) {
            this.status = status
        },

        setDates(dates: any[]) {
            this.dates = dates
        },

        onPage(event: DataTablePageEvent) {
            const pageNumber = event.first / event.rows + 1;
            this.fetchItems(pageNumber)
        },

        fetchItems(pageNumber?: number) {
            const params = {
                page: pageNumber ?? this.page,
                rows: this.rows,
                dates: this.dates,
                status: this.status,
            }

            quoteService.paginate(params)
                .then((response: AxiosResponse) => {
                    const pagination = response.data
                    this.sales = [...pagination.data]
                    this.totalRecords = pagination.total
                })
        },

        deleteItem(url: string) {
            return quoteService.deleteItem(url)
        }
    },
})
