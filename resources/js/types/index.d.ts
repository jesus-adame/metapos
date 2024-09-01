import { Config } from 'ziggy-js';

// Escribe aquí tus tipos

export interface User {
    id: number;
    name: string;
    lastname: string;
    email: string;
    email_verified_at: string;
    roles: Role[];
    permissions: Permission[];
}

export interface Customer {
    id: number;
    name: string;
    lastname: string;
    email: string;
    phone: string;
    address: string;
}

export interface Sale {
    id: number
    customer?: Customer
    seller: User
    cash_register: CashRegister
    total: number
    status: string
    payments: Payment[]
    products: Product[]
}

export interface Purchase {
    id: number
    supplier?: Supplier
    seller: User
    cash_register?: CashRegister
    location: Location
    total: number
    status: string
    payments: Payment[]
    products: Product[]
}

export interface Payment {
    id: number
    method: string
}

export interface Supplier {
    id: number;
    name: string;
    lastname?: string;
    email?: string;
    phone?: string;
    address?: string;
    company_name?: string;
    rfc?: string;
}

export interface CashRegister {
    id?: number,
    name?: string
}

export interface Location {
    id?: string
    name?: string
    address?: string
    phone_number?: string
    rfc?: string
    email?: string
    type: string
    is_default: boolean
}

export interface Product {
    id?: number
    name: string
    code: string
    sku: ?string
    price: number
    cost: number
    image: string
    image_url: string
    quantity: number
    stock: number
    unit_type: string
    tax: number
    description?: string
}

export interface CartItem {
    id?: number
    name: string
    code: string
    price: number
    image: string
    image_url: string
    quantity: number
    stock: number
    unit_type: string
    tax: number
}

export interface CashFlow {
    id?: string
    amount?: number
    method?: string
    type?: string
    description?: string
}

export interface InventoryTransaction {
    id?: string
    amount?: number
    type?: string
    description?: string
}

export interface SuccessResponse {
    message: string;
    // Other properties as needed
}

export interface ErrorResponse {
    message: string;
    errors: {};
    // Other error properties as needed
}

export interface TabItem {
    icon: string,
    label: string,
    route: string,
    active: boolean,
    permission: string
}

export interface Role {
    id: ?number
    name: string
    permissions: Permission[]
}

export interface Permission {
    id: ?number
    name: string
}

export interface Setting {
    id: ?number
    key: string
    label: string
    value: string
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
    cashRegister: CashRegister,
    cashRegisters: CashRegister[],
    location: Location,
    locations: Location[],
    csrf_token: string,
    location_id: number,
    permissions: string[],
    roles: string[],
};