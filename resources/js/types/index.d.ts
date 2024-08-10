import { Config } from 'ziggy-js';

// Escribe aquí tus tipos

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Customer {
    id: number;
    name: string;
    email: string;
    phone: string;
}

export interface Supplier {
    id: number;
    name: string;
    email: string;
    phone: string;
}

export interface CashRegister {
    id?: number,
    name?: string
}

export interface Branch {
    id?: string
    name?: string
}

export interface Product {
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
    // Other error properties as needed
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };

    cashRegister: CashRegister,
    cashRegisters: CashRegister[],
    branch: Branch,
    branches: Branch[],
    csrf_token: string
};