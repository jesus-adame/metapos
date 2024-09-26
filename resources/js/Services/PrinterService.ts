import axios from "axios";
import qz from 'qz-tray';
import { useToast } from 'primevue/usetoast';
import { connectQZ, disconnectQZ } from "@/helpers";

export default class PrinterService {
    private toast;

    constructor() {
        this.toast = useToast()
    }

    connect() {
        connectQZ()
    }

    disconect() {
        disconnectQZ()
    }

    print = (pdfUrl: string, printer: string) => {
        axios.get(pdfUrl, {
            responseType: 'arraybuffer' // Para obtener el archivo binario
        }).then(response => {
            const config = qz.configs.create(printer)
            const base64PDF = btoa(
                new Uint8Array(response.data).reduce((data, byte) => data + String.fromCharCode(byte), '')
            );

            // Definir los datos para QZ Tray
            const data: any[] = [{
                type: 'pdf',
                format: 'base64',
                data: base64PDF
            }];

            // Enviar a imprimir
            qz.print(config, data).then(() => {
                this.toast.add({ severity: 'success', summary: 'Correcto', detail: 'Imprimiendo documento', life: 1100 });
            }).catch((err: any) => console.error("Error de impresión:", err));
        }).catch(error => {
            console.error("Error al obtener el PDF:", error);
        });
    }
}