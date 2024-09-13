import { Location, Payment, PaymentMethod } from '@/types';
import { usePage } from '@inertiajs/vue3';
import moment from 'moment-timezone';

export const formatMoneyNumber = (number: number | null) => {
    if (number == null) return null
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(number);
}

export const percentageNumber = (number: number | null) => {
    if (number == null) return null
    return new Intl.NumberFormat('es-MX', {
        style: 'percent',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(number / 100);
}

export const formatDateTime = (date: string, local: boolean = false) => { // Asegúrate de que el nombre sea correcto
    const dateTime = moment.utc(date)
    const timezone = moment.tz.guess()

    if (!dateTime.isValid()) {
        console.error('El valor proporcionado a datetimeFormat no es una fecha válida.');
        return date;
    }

    if (local) {
        return dateTime.format('DD/MM/YYYY h:mm a')
    }

    return dateTime.tz(timezone).format('DD/MM/YYYY h:mm a')
}

export const formatTime = (date: string, local: boolean = false) => { // Asegúrate de que el nombre sea correcto
    const dateTime = moment.utc(date)
    const timezone = moment.tz.guess()

    if (!dateTime.isValid()) {
        console.error('El valor proporcionado a datetimeFormat no es una fecha válida.');
        return date;
    }

    if (local) {
        return dateTime.format('h:mm a')
    }

    return dateTime.tz(timezone).format('h:mm a')
}

export const formatDate = (date: string, local: boolean = false) => { // Asegúrate de que el nombre sea correcto
    const dateTime = moment.utc(date)
    const timezone = moment.tz.guess()

    if (!dateTime.isValid()) {
        console.error('El valor proporcionado a datetimeFormat no es una fecha válida.');
        return date;
    }

    if (local) {
        return dateTime.format('DD/MM/YYYY')
    }

    return dateTime.tz(timezone).format('DD/MM/YYYY');
}

export const locationIcon = (location: Location | null) => {
    if (location == null) {
        return ''
    }

    if (location.type == 'branch') {
        return 'pi pi-building'
    }

    return 'pi pi-box'
}

export const saleStatus = (status: string) => {
    switch (status) {
        case 'completed':
            return 'Pagada'
        case 'pending':
            return 'Pendiente'
        case 'canceled':
            return 'Cancelada'
        default:
            return ''
    }
}

export const saleSeverity = (status: string) => {
    switch (status) {
        case 'completed':
            return 'success'
        case 'pending':
            return 'warning'
        case 'canceled':
            return 'danger'
        default:
            return ''
    }
}

export const purchaseStatus = (status: string) => {
    switch (status) {
        case 'completed':
            return 'Pagado'
        case 'pending':
            return 'Pendiente'
        case 'canceled':
            return 'Cancelado'
        default:
            return ''
    }
}

export const purchaseSeverity = (status: string) => {
    switch (status) {
        case 'completed':
            return 'success'
        case 'pending':
            return 'warning'
        case 'canceled':
            return 'danger'
        default:
            return ''
    }
}

export const calculateMetodIcon = (payment: PaymentMethod) => {
    switch (payment.code) {
        case 'cash': {
            return 'pi-money-bill';
        }
        case 'card': {
            return 'pi-credit-card';
        }
        case 'transfer': {
            return 'pi-arrow-right';
        }
    }
}

export function roundBank(num: number) {
    return num % 1 === 0.5 ? (num % 2 === 0 ? num : num + 1) : Math.round(num);
}

export function getPercentage(number: number, percentage: number) {
    // Convert the percentage to a decimal by dividing by 100
    const percentageDecimal = percentage / 100;

    // Calculate the result by multiplying the number by the decimal percentage
    return number * percentageDecimal;
}

export function hardReload() {
    window.location.reload()
}

export function can(ability: string) {
    const page = usePage();
    const superAdmin = 'Super Admin'

    return page.props.permissions.some((value: string) => value == ability)
        || page.props.roles.some((role: string) => role == superAdmin);
}

export function hasRole(name: string) {
    const page = usePage();
    return page.props.roles.some((value: string) => value == name);
}