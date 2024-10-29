import { ROLES } from '@/consts/role_permissions';
import { Location, PaymentMethod } from '@/types';
import { usePage } from '@inertiajs/vue3';
import moment from 'moment-timezone';
import qz from 'qz-tray';

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
    // Multiplicamos por 100 para trabajar con centésimas
    const scaled = num * 100;

    // Obtenemos la parte decimal de las centésimas
    const decimal = scaled - Math.floor(scaled);

    let rounded: number;
    if (decimal === 0.5) {
        // Si termina en .5, mantenemos el valor
        rounded = scaled;
    } else {
        // En otro caso, redondeamos
        rounded = Math.round(scaled);
    }

    // Dividimos por 100 para volver a la escala original y fijamos a 2 decimales
    return Number((rounded / 100).toFixed(2));
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
    const { props } = usePage();

    return props.permissions.some((value: string) => value === ability)
        || props.roles.some((role: string) => role === ROLES.SUPER_ADMIN);
}

export function hasRole(name: string) {
    const page = usePage();
    return page.props.roles.some((value: string) => value == name);
}

export function getPrinter() {
    return localStorage.getItem('selectedPrinter') ?? '';
}

export function getLabelPrinter() {
    return localStorage.getItem('selectedLabelPrinter') ?? '';
}

export const signingQZ = () => {
    qz.security.setCertificatePromise(function (resolve, reject) {
        fetch("/assets/signing/digital-certificate.txt", { cache: 'no-store', headers: { 'Content-Type': 'text/plain' } })
            .then(function (data: any) { data.ok ? resolve(data.text()) : reject(data.text()); });
    });

    qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
    qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
            fetch("/qz/sign-message?token=" + toSign, { cache: 'no-store', headers: { 'Content-Type': 'text/plain' } })
                .then(function (data: any) { data.ok ? resolve(data.text()) : reject(data.text()); });
        };
    });
}

export const disconnectQZ = () => {
    if (qz.websocket.isActive()) {
        qz.websocket.disconnect().catch(err => console.error(err));
    }
}

export const connectQZ = () => {
    signingQZ()
    if (!qz.websocket.isActive()) {
        qz.websocket.connect().catch((err: any) => console.error(err));
    }
}