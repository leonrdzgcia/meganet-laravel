<template>

    <div class="mt-3">
        <div class="card">
            <div class="card-header float-right customer-buttons-wrapper" style="background-color: #fbfaff; align-self: self-end;">
                <button
                    class="btn btn-primary"
                    type="button"
                    @click="createInvoice"
                >
                    Crear Factura Manual
                </button>
            </div>
        </div>
    </div>


    <Datatable
        :id="id"
        idTable="clientinvoisetable"
        module="cliente/billing/invoice"
        model="ClientInvoice"
        list="Listado de Facturas"
        :buttons="{
            reload_transaction_table: {
                class: 'btn btn-outline-info waves-effect waves-light',
                iclass: 'fas fa-history',
                href: 'javascript:void(0)',
                id: 'button-reload-table-invoice',
            },
            upload: {
                class: 'btn btn-outline-info waves-effect waves-light',
                iclass: 'fa fa-plus',
                href: 'javascript:void(0)',
                id: 'buttonmodalinvoice',
            }
        }"
        :editButton="{ modal: 'modalinvoice' }"
        @clientinvoisetable="table"
        :add="null"
        :cssCard="false"
        @item-delete="itemDelete"
    />

</template>

<script>
import DatatableHelper from "../../../../../helpers/datatableHelper";
import {onMounted, reactive, ref, watch} from "vue";
import Datatable from "../../../../base/shared/Datatable";
import Modal from "../../../../../helpers/modal";
import { requestCreateClientInvoice } from "./helpers/helper";


export default {
    name: "ClientInvoice",
    props: {
        id: {
            type: String,
            default: null,
        },
        editModal: Object,
    },
    components: {Datatable},
    setup(props) {
        const datatable = reactive({
            table: new DatatableHelper({}),
        });
        const modal = ref();
        const action = ref(`crear/${props.id}`);

        watch(
            () => props.editModal,
            (data, dataBefore) => {
                if (data.value && data.toggleModal == "modalinvoice") {
                    showEditModal(data.key);
                }
            }
        );

        onMounted(async () => {
            modal.value = new Modal("modalinvoice");
            // transaction.value = await transactions(props.id);

            $(document).on("click", "#buttonmodalinvoice", function () {
                // showAddModal();
            });

            $(document).on("click", "#button-reload-table-invoice", function () {
                datatable.table.reload();
            });
        });

        const table = (refTable) => {
            datatable.table = new DatatableHelper(refTable);
        };

        const cleanModal = async () => {
            modal.value.hide();
            action.value = `crear/${props.id}`;
            datatable.table.reload();
            // transaction.value = await transactions(props.id);
        };

        const itemDelete = async () => {
            // transaction.value = await transactions(props.id);
        }

        const showAddModal = () => {
            action.value = `crear/${props.id}`;
            modal.value.show();
        };

        const showEditModal = (idItem) => {
            action.value = `update/${idItem}`;
            modal.value.show();
        };

        const createInvoice = async () => {
            await requestCreateClientInvoice(props.id);
        }

        return {
            modal,
            cleanModal,
            table,
            datatable,
            action,
            itemDelete,
            createInvoice
        };

    },

};
</script>

<style scoped>

</style>
