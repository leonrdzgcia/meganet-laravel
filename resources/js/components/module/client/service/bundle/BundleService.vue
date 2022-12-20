<template>
    <div class="col-12" v-if="loadDatatable && allService.Bundle">
        <div class="card">
            <Datatable
                idTable="bundleservicetable"
                module="cliente/clientbundleservice"
                model="ClientBundleService"
                list="Listado de Servicios Bundle"
                :buttons="getButtonDatatable()"
                :editButton="{ modal: 'modalbundleservice' }"
                @bundleservicetable="documentTable"
                :add="null"
                :cssCard="false"
                :id="idClient"
            />
        </div>
    </div>
    <ClientCrudBundleService
        :module="'cliente/clientbundleservice'"
        :action="actionCrudBundleService"
        :idClient="idClient"
        :render="render"
        @cleanModal="cleanModal"
    />
</template>

<script>
import Datatable from "../../../../base/shared/Datatable";
import {onBeforeMount, onMounted, reactive, ref, watch} from "vue";
import DatatableHelper from "../../../../../helpers/datatableHelper";
import Modal from "../../../../../helpers/modal";
import ClientCrudBundleService from "./ClientCrudBundleService";
import Permission from "../../../../../helpers/Permission";
import {allViewHasPermission} from "../../../../../helpers/Request";
import {hasService} from "../../helpers/helper";

export default {
    name: "BundleService",
    props: {
        idClient: {
            type: String,
            default: null,
        },
        editModal: Object,
        showAddService: {
            type: Boolean,
            default: false
        }
    },
    emits: ["resetShowAddService"],
    components: {
        Datatable,
        ClientCrudBundleService,
    },
    setup(props, {emit}) {
        const datatable = reactive({
            table: new DatatableHelper({}),
        });
        const modal = ref();
        const actionCrudBundleService = ref(`crear/${props.idClient}`);
        const render = ref(1);

        const loadDatatable = ref(false);
        const hasPermission = reactive({
            data: new Permission({})
        })
        const allService = reactive({
            Bundle: false
        });

        watch(
            () => props.showAddService,
            (data, dataBefore) => {
                if (data) {
                    showAddModal();
                }
            }
        );

        watch(
            () => props.editModal,
            (data, dataBefore) => {
                if (data.value && data.toggleModal == 'modalbundleservice') {
                    showEditModal(data.key);
                }
            }
        );

        onBeforeMount(async () => {
            hasPermission.data = new Permission(await allViewHasPermission());
            modal.value = new Modal("modalbundleservice");

            if (hasPermission.data.canView('client_service_bundle_add')) {
                $(document).on("click", "#buttonmodalbundleservice", function () {
                    showAddModal();
                });
            }

            allService.Bundle = await hasService(props.idClient, 'bundle_service')
            //TODO documentar Para Cargue el datable despues de inicializar las variables
            loadDatatable.value = true;
        });

        const cleanModal = async () => {
            allService.Bundle = await hasService(props.idClient, 'bundle_service')
            emit('resetShowAddService', 'bundle')
            modal.value.hide();
            render.value++;
            actionCrudBundleService.value = `crear/${props.idClient}`;
            if (datatable.table) datatable.table.reload();
        };

        const documentTable = (refTable) => {
            datatable.table = new DatatableHelper(refTable);
        };

        const showAddModal = () => {
            actionCrudBundleService.value = `crear/${props.idClient}`;
            modal.value.show();
        };

        const showEditModal = (idItem) => {
            actionCrudBundleService.value = `update/${idItem}`;
            modal.value.show();
        };

        const getButtonDatatable = () => {
            let buttons = {};
            if (hasPermission.data.canView('client_service_bundle_add')) {
                buttons.upload = {
                    class: 'btn btn-outline-info waves-effect waves-light',
                    iclass: 'fa fa-plus',
                    href: 'javascript:void(0)',
                    id: 'buttonmodalbundleservice',
                };
            }
            return buttons;
        }

        return {
            modal,
            cleanModal,
            documentTable,
            datatable,
            actionCrudBundleService,
            render,
            getButtonDatatable,
            hasPermission,
            loadDatatable,
            allService
        };
    },
};
</script>

<style scoped></style>
