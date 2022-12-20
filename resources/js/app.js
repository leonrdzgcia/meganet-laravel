require('./bootstrap');
import { createApp } from 'vue';

//Dashboard
import Dashboard from "./components/module/dashboard/Dashboard"

//Planes
import InternetCrud from "./components/module/planes/InternetCrud"
import VozCrud from "./components/module/planes/VozCrud"
import CustomCrud from "./components/module/planes/CustomCrud"
import BundleCrud from "./components/module/planes/BundleCrud"

//CRM
import CrmCrud from "./components/module/crm/CrmCrud"
import AddCrmCrud from "./components/module/crm/AddCrmCrud"

//Client
import ClientCrud from "./components/module/client/ClientCrud"
import AddClientCrud from "./components/module/client/AddClientCrud"

import Datatable from "./components/base/shared/Datatable"

import Message from  './shared/Message';
import Breadcrumb from  './components/base/shared/Breadcrumb';

//Router
import AddRouterCrud from './components/module/router/AddRouterCrud';
import RouterCrud from './components/module/router/RouterCrud';

//network
import NetworkListar from './components/module/network/NetworkListar';
import AddNetworkCrud from './components/module/network/AddNetworkCrud';
import NetworkVer from './components/module/network/NetworkVer';

//Administracion
import RolListar from './components/module/adminstration/rol/RolListar';
import PartnerListar from './components/module/adminstration/partner/PartnerListar';
import LocationListar from './components/module/adminstration/location/LocationListar';
import StateListar from './components/module/adminstration/state/StateListar';
import MunicipalityListar from './components/module/adminstration/municipality/MunicipalityListar';
import ColonyListar from './components/module/adminstration/colony/ColonyListar';
import MethodOfPaymentListar from './components/module/adminstration/methodofpayment/MethodOfPaymentListar';
import IftListar from './components/module/adminstration/ift/IftListar';

//Configuracion
import DebtPaymentClient from './components/module/setting/DebtPaymentClient';
import DebitCustomListar from './components/module/setting/DebitCustomListar';

//Mapas
import GoogleMap from './components/base/googlemap/GoogleMap';

//Ticket
import DashboardTicket from './components/module/tickets/DashboardTicket';
import TicketCrud from './components/module/tickets/TicketCrud';
import VerTicket from './components/module/tickets/VerTicket';

//Topbar
import NotificationTopbar from './shared/NotificationTopbar';

//Perfil
import Perfil from './components/perfil/Perfil';
import PerfilEdit from './components/perfil/PerfilEdit';

createApp({
    components: {
        //Dashboard
        Dashboard,


        // Planes
        InternetCrud,
        VozCrud,
        CustomCrud,
        BundleCrud,

        // CRM
        CrmCrud,
        AddCrmCrud,

        //CLIENT
        ClientCrud,
        AddClientCrud,

        //Router
        AddRouterCrud,
        RouterCrud,

        //network
        AddNetworkCrud,
        NetworkListar,
        NetworkVer,

        //Mapa
        GoogleMap,

        DebtPaymentClient,

        //Administracion
        //Rol
        RolListar,
        //Socio
        PartnerListar,
        //Ubicacion
        LocationListar,
        //Estado
        StateListar,
        //Municipio
        MunicipalityListar,
        //Colonia
        ColonyListar,
        MethodOfPaymentListar,
        //Ift
        IftListar,

        //Ticket
        DashboardTicket,
        TicketCrud,
        VerTicket,

        NotificationTopbar,
        Datatable,
        Message,
        Breadcrumb,

        //Perfil
        Perfil,
        PerfilEdit,

        //Setting
        DebitCustomListar
    }
}).mount('#init-vue');
