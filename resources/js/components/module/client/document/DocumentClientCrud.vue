<template>
    <div v-if="hasPermission.data.canView('client_document')">
        <Datatable
            idTable="documenttable"
            :id="id"
            module="cliente/document"
            model="DocumentClient"
            :buttons="getButtonDatatable()"
            :editButton="{ modal: 'modalDocument' }"
            @documenttable="documentTable"
            :add="null"
        />

        <UploadDocumentClient
            :module="'cliente/document'"
            :action="actionUploadDocument"
            :idClient="id"
            @cleanModal="cleanModal"
        />
    </div>
</template>

<script>
import Modal from "../../../../helpers/modal";
import Permission from "../../../../helpers/Permission";
import DatatableHelper from "../../../../helpers/datatableHelper";
import Datatable from "../../../base/shared/Datatable";
import UploadDocumentClient from "./UploadDocumentClient";
import {reactive, ref, onMounted, watch, onBeforeMount} from "vue";
import {allViewHasPermission} from '../../../../helpers/Request';

export default {
    name: "DocumentClientCrud",
    props: {
        id: {
            type: String,
            default: null,
        },
        editModal: Object,
    },
    components: {
        Datatable,
        UploadDocumentClient,
    },
    setup(props) {
        const datatable = reactive({
            table: new DatatableHelper({}),
        });
        const modal = reactive({
            data: {}
        })
        const actionUploadDocument = ref(`add/${props.id}`);
        const hasPermission = reactive({
            data: new Permission({})
        })

        watch(
            () => props.editModal,
            (data, dataBefore) => {
                if (data.value && data.toggleModal == 'modalDocument'){
                    showEditModal(data.key);
                }
            }
        );

        onBeforeMount(async () => {
            hasPermission.data = new Permission(await allViewHasPermission());
            modal.data = new Modal("modalDocument");
            if (hasPermission.data.canView('client_document_add')){
                $(document).on("click", "#buttonmodaluploaddocument", function () {
                    showAddModal();
                });
            }
        });

        const cleanModal = () => {
            modal.data.hide();
            actionUploadDocument.value = `add/${props.id}`;
            datatable.table.reload();
        };

        const documentTable = (refTable) => {
            datatable.table = new DatatableHelper(refTable);
        };

        const showAddModal = () => {
            actionUploadDocument.value = `add/${props.id}`;
            modal.data.show();
        }

        const showEditModal = (id) => {
            actionUploadDocument.value = `update/${id}`;
            modal.data.show();
        };

        const getButtonDatatable = () => {
            let buttons = {};
            if (hasPermission.data.canView('client_document_add')){
                buttons.upload = {
                    class: 'btn btn-outline-info waves-effect waves-light',
                    iclass: 'fa fa-plus',
                    href: 'javascript:void(0)',
                    id: 'buttonmodaluploaddocument'
                };
            }
            return buttons;
        }

        return {
            modal,
            cleanModal,
            documentTable,
            datatable,
            actionUploadDocument,
            getButtonDatatable,
            hasPermission
        };
    },
};
</script>

<style scoped></style>
