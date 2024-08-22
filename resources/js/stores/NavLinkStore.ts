import { TabItem } from "@/types"
import { usePage } from "@inertiajs/vue3"
import { defineStore } from "pinia"

const page = usePage()

interface NavStore {
    tabItems: TabItem[] | null,
}

export const useNavStore = defineStore('nav', {
    state: (): NavStore => ({
        tabItems: tabItems,
    }),
    getters: {
        getLinks: (state) => state.tabItems
    },
    actions: {
        //
    },
})

const tabItems: TabItem[] = [
    {
        icon: 'fi fi-sr-apps',
        label: 'Panel',
        route: route('home'),
        active: route().current('home'),
        permission: 'view finances'
    },
    {
        icon: 'fi fi-sr-shopping-cart',
        label: 'POS',
        route: route('sales.create'),
        active: route().current('sales.create'),
        permission: 'create sales'
    },
    {
        icon: 'fi fi-sr-drawer-empty',
        label: 'Caja',
        route: route('cash-flows.index'),
        active: route().current('cash-flows.index'),
        permission: 'view sales'
    },
    {
        icon: 'fi fi-sr-tags',
        label: 'Ventas',
        route: route('sales.index'),
        active: route().current('sales.index'),
        permission: 'view sales'
    },
    {
        icon: 'fi fi-sr-bags-shopping',
        label: 'Compras',
        route: route('purchases.index'),
        active: route().current('purchases.index'),
        permission: 'view purchases'
    },
    // {
    //   icon: 'fi fi-sr-bank',
    //   label: 'Banco',
    //   route: route('bank-transactions.index'),
    //   active: route().current('bank-transactions.index')
    // },
    {
        icon: 'fi fi-sr-boxes',
        label: 'Inventario',
        route: route('products.index'),
        active: route().current('products.index'),
        permission: 'view products'
    },
    // {
    //   icon: 'fi fi-sr-users-alt',
    //   label: 'Usuarios',
    //   route: route('users.index'),
    //   active: route().current('users.index'),
    //   permission: 'view users'
    // },
];