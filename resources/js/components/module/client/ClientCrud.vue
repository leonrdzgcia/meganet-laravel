<template>
    <div>
        <tabs @changeTab="changeTab">
            <tab title="Información" tab="Informacion" :active="true" v-if="showTab.Informacion">
                <InformationClientCrud
                    :action="`update/${id}`"
                    :id="id"
                    @getTypeOfBilling="getTypeOfBilling"
                />
            </tab>
            <tab title="Documentos" tab="Documentos" v-if="showTab.Documentos">
                <DocumentClientCrud
                    :id="id"
                    v-if="tabs.Documentos"
                    :editModal="editModal"
                ></DocumentClientCrud>
            </tab>
            <tab title="Servicios" tab="Servicios" v-if="showTab.Servicios">
                <ClientService
                    :id="id"
                    v-if="tabs.Servicios"
                    :editModal="editModal"
                />
            </tab>
            <tab title="Facturación" tab="Facturacion" v-if="showTab.Facturacion">
                <ClientBilling
                    :id="id"
                    :typeOfBilling="typeBilling"
                    v-if="tabs.Facturacion"
                    :editModal="editModal"
                />
            </tab>
        </tabs>
    </div>
</template>

<script>
import Tabs from "../../base/shared/tabs/Tabs";
import Tab from "../../base/shared/tabs/Tab";
import InformationClientCrud from "./InformationClientCrud";
import ClientService from "./service/ClientService";
import { ref, reactive, onMounted } from "vue";
import { editModal, showEditModal } from "../../../hook/modalHook";
import ClientBilling from "./billing/ClientBilling";
import DocumentClientCrud from "./document/DocumentClientCrud";

export default {
    name: "ClientCrud",
    props: {
        id: {
            type: String,
            default: null,
        },
        show: String
    },
    components: {
        DocumentClientCrud,
        ClientBilling,
        Tabs,
        Tab,
        InformationClientCrud,
        ClientService,
    },
    setup(props) {
        const showTab = ref(_.mapKeys(JSON.parse(props.show) , v => v ));

        const tabs = reactive({
            Servicios: false,
            Facturacion: false,
            Documentos: false
        });

        const typeBilling = ref("");

        const changeTab = ({ tab }) => {
            tabs[tab] = true;
        };

        const getTypeOfBilling = ({ typeOfBilling }) => {
            typeBilling.value = typeOfBilling;
        };

        onMounted(() => {
            $(document).on("click", ".uil-pen-modal", function () {
                let idItem = $(this).parent().attr("id-item");
                let modal = $(this).parent().attr("toggle-modal");
                showEditModal(idItem, modal);
            });
        });

        return {
            changeTab,
            tabs,
            editModal,
            typeBilling,
            getTypeOfBilling,
            showTab
        };
    },
};
</script>

<style scoped></style>
