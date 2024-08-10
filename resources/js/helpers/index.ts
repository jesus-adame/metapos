import { Location } from '@/types';
import moment from 'moment-timezone';

export const formatMoneyNumber = (numb: number) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(numb);
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

export const locationIcon = (location: Location) => {
    if (location.type == 'branch') {
        return 'pi pi-building'
    }

    return 'pi pi-box'
}