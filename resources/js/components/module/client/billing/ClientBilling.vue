<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="p-3">
                    <tabs @changeTab="changeTab">
                        <tab
                            title="Vista de Facturacion"
                            tab="InfoFacturacion"
                            :active="true"
                        >
                            <ViewClientBilling
                                :id="id"
                                :typeOfBilling="typeOfBilling"
                            />
                        </tab>
                        <tab title="Transacciones" tab="Transacciones">
                            <ViewClientTransaction
                                :id="id"
                                v-if="tabs.Transacciones"
                                :editModal="editModal"
                            />
                        </tab>
                        <tab title="Facturas" tab="Facturas"> <ClientInvoice
                            :id="id"
                            v-if="tabs.Facturas"
                            :editModal="editModal"
                        />
                        </tab>
                        <tab title="Pagos" tab="Pagos">
                            <ViewClientPayment
                                :id="id"
                                v-if="tabs.Pagos"
                                :editModal="editModal"
                            />
                        </tab>
                    </tabs>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Tabs from "../../../base/shared/tabs/Tabs";
import Tab from "../../../base/shared/tabs/Tab";
import { onMounted, reactive } from "vue";
import ViewClientBilling from "./ViewClientBilling";
import ViewClientPayment from "./payment/ViewClientPayment";
import ClientInvoice from "./invoice/ClientInvoice";
import ViewClientTransaction from "./transaction/ViewClientTransaction";

export default {
    name: "ClientBilling",
    props: {
        id: {
            type: String,
            default: null,
        },
        typeOfBilling: String,
        editModal: Object,
    },
    components: {
        ViewClientPayment,
        ViewClientBilling,
        ViewClientTransaction,
        ClientInvoice,
        Tabs,
        Tab,
    },
    setup() {
        const tabs = reactive({
            InfoFacturacion: false,
            Transacciones: false,
            Facturas: false,
            Pagos: false,
        });

        const changeTab = ({ tab }) => {
            tabs[tab] = true;
        };

        return {
            changeTab,
            tabs,
        };
    },
};
</script>

<style scoped></style>
