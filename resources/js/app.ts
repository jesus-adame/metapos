import './bootstrap';
import '../css/app.css';
import "../css/flags.css";
import 'primeicons/primeicons.css'
import '@flaticon/flaticon-uicons/css/all/all.css'

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import PrimeVue from 'primevue/config';
import Toast from 'primevue/toast';
import Message from 'primevue/message';
import Image from 'primevue/image';
import AutoComplete from 'primevue/autocomplete';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Checkbox from 'primevue/checkbox';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Textarea from 'primevue/textarea';
import Password from 'primevue/password';
import Select from 'primevue/select';
import MultiSelect from 'primevue/multiselect';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Dialog from 'primevue/dialog';
import ConfirmDialog from 'primevue/confirmdialog';
import Tag from 'primevue/tag';
import InputMask from 'primevue/inputmask';
import Breadcrumb from 'primevue/breadcrumb';
import ContextMenu from 'primevue/contextmenu';
import Card from 'primevue/card';
import VirtualScroller from 'primevue/virtualscroller';
import DynamicDialog from 'primevue/dynamicdialog';
import Tree from 'primevue/tree';
import TreeSelect from 'primevue/treeselect';
import Accordion from 'primevue/accordion';
import AccordionPanel from 'primevue/accordionpanel';
import AccordionHeader from 'primevue/accordionheader';
import AccordionContent from 'primevue/accordioncontent';
import SelectButton from 'primevue/selectbutton';
import ToggleSwitch from 'primevue/toggleswitch';
import MegaMenu from 'primevue/megamenu';
import FileUpload from 'primevue/fileupload';
import DatePicker from 'primevue/datepicker';
import Avatar from 'primevue/avatar';
import AvatarGroup from 'primevue/avatargroup';
import Menu from 'primevue/menu';
import Drawer from 'primevue/drawer';
import PanelMenu from 'primevue/panelmenu';
import Panel from 'primevue/panel';
import FloatLabel from 'primevue/floatlabel';
import Chart from 'primevue/chart';

import ToastService from 'primevue/toastservice';
import DialogService from 'primevue/dialogservice';
import ConfirmationService from 'primevue/confirmationservice';

import primeOptions from './presets/languages'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })

        app.component('Toast', Toast)
            .component('Message', Message)
            .component('Image', Image)
            .component('InputText', InputText)
            .component('InputNumber', InputNumber)
            .component('Dropdown', Dropdown)
            .component('Checkbox', Checkbox)
            .component('AutoComplete', AutoComplete)
            .component('IconField', IconField)
            .component('InputIcon', InputIcon)
            .component('InputGroup', InputGroup)
            .component('Textarea', Textarea)
            .component('InputGroupAddon', InputGroupAddon)
            .component('Password', Password)
            .component('Select', Select)
            .component('MultiSelect', MultiSelect)
            .component('DataTable', DataTable)
            .component('Column', Column)
            .component('ColumnGroup', ColumnGroup)
            .component('Row', Row)
            .component("Dialog", Dialog)
            .component("ConfirmDialog", ConfirmDialog)
            .component("Tag", Tag)
            .component("InputMask", InputMask)
            .component("Breadcrumb", Breadcrumb)
            .component("ContextMenu", ContextMenu)
            .component("Card", Card)
            .component("VirtualScroller", VirtualScroller)
            .component("DynamicDialog", DynamicDialog)
            .component("Tree", Tree)
            .component("TreeSelect", TreeSelect)
            .component("Accordion", Accordion)
            .component("AccordionPanel", AccordionPanel)
            .component("AccordionHeader", AccordionHeader)
            .component("AccordionContent", AccordionContent)
            .component("SelectButton", SelectButton)
            .component("ToggleSwitch", ToggleSwitch)
            .component("MegaMenu", MegaMenu)
            .component("FileUpload", FileUpload)
            .component("DatePicker", DatePicker)
            .component("Avatar", Avatar)
            .component("AvatarGroup", AvatarGroup)
            .component("Menu", Menu)
            .component("Panel", Panel)
            .component("PanelMenu", PanelMenu)
            .component("Drawer", Drawer)
            .component("FloatLabel", FloatLabel)
            .component("Chart", Chart)

        app.use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, primeOptions)
            .use(ToastService)
            .use(DialogService)
            .use(ConfirmationService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
