import SaleService from "@/Services/SaleService"
import { CashRegister, Sale } from "@/types"
import { AxiosResponse } from "axios"
import { defineStore } from "pinia"
import { DataTablePageEvent } from "primevue/datatable"

interface SaleState {
    selectedCashRegister: CashRegister | null,
    sales: Sale[],
    totalRecords: number,
    rows: number,
    page: number,
    totalSales: number,
    dates: any[],
    status: string | null,
    statusList: any[],
}

const saleService = new SaleService()

export const useSaleStore = defineStore('sale', {
    state: (): SaleState => ({
        selectedCashRegister: null,
        sales: [],
        totalRecords: 0,
        totalSales: 0,
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
        setCashRegister(selectedCashRegister: CashRegister | null) {
            if (selectedCashRegister == null) {
                this.selectedCashRegister = null
                return;
            }
            this.selectedCashRegister = { ...selectedCashRegister }
        },

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
                cash_register: this.selectedCashRegister?.id,
                dates: this.dates,
                status: this.status,
            }

            saleService.paginate(params)
                .then((response: AxiosResponse) => {
                    const pagination = response.data
                    this.sales = [...pagination.data]
                    this.totalRecords = pagination.total
                    this.totalSales = pagination.totalSales
                })
        },

        deleteItem(url: string) {
            return saleService.deleteItem(url)
        }
    },
})
