<template>
    <div class="row">
        <div class="col-12">
            <div :class="{ card: cssCard }">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{ list }}</h4>
                        <button
                            type="button"
                            class="btn btn-outline-info"
                            data-bs-toggle="modal"
                            data-bs-target="#modaleditcolumn"
                            @click="showModal"
                            style="margin-left: auto; margin-right: 10px;"
                        >
                            ...
                        </button>
                        <div v-if="lengthButtons">
                            <a
                                v-for="button in buttons"
                                :href="button.href"
                                :class="button.class"
                                :id="button.id"
                            >
                                <i :class="button.iclass"></i>
                            </a>
                        </div>

                    <div v-if="add">
                        <a
                            :href="`/${module}/crear`"
                            class="btn btn-success waves-effect waves-light"
                        >
                            {{ add }}
                        </a>
                    </div>
                </div>
                <div class="justify-content-end">
                </div>
                <div class="card-body">
                    <table :id="idTable" class="table table-striped hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th
                                v-for="val in headers"
                                :class="val.class"
                            >
                                {{ val.label }}
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <div class="responsive">
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <div
        class="modal fade"
        id="modaleditcolumn"
        data-backdrop="static"
        data-keyboard="false"
    >
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="m-auto">{{ modalTitle }}</h6>
                </div>
                <div class="modal-body m-0">
                    <form
                        method="POST"
                        @submit.prevent="onSubmit"
                        @change="dataForm.data.errors.clear($event.target.name)"
                        @keydown="
                            dataForm.data.errors.clear($event.target.name)
                        "
                    >
                        <div v-for="val in fieldsJson">
                            <ComponentFormDefault
                                v-if="val.include"
                                :json="val"
                                :errors="dataForm.data.errors"
                                :key="val"
                                v-model="dataForm.data[val.field]"
                                @update-field="updateThisField"
                                @clear-error="clearError"
                            />
                        </div>

                        <div class="form-group text-center">
                            <button
                                type="button"
                                class="btn btn-light waves-effect me-2"
                                data-bs-dismiss="modal"
                            >
                                Cerrar
                            </button>
                            <button class="btn btn-primary" type="submit">
                                Aplicar
                            </button>
                        </div>
                    </form>
                </div>
                <br/>
            </div>
        </div>
    </div>
</template>

<script>
import {initDatatable} from "../../../helpers/InitDatatable";
import {ref, watch, reactive, onMounted} from "vue";
import {
    requestColumnsDatatableByModule,
    requestAllColumnsDatatableByModule,
} from "../../../helpers/Request";
import {deleteRowDatatable} from "../../../hook/datatableHook";
import ComponentFormDefault from "../../../components/ComponentFormDefault";
import Modal from "../../../helpers/modal";
import Form from "../../../helpers/Form";

export default {
    name: "Datatable",
    props: {
        idTable: {
            type: String,
            default: "table",
        },
        buttons: {
            type: Object,
            default: {},
        },
        module: String,
        model: {
            type: String,
            default: "",
        },
        list: {
            type: String,
            default: "",
        },
        add: {
            type: String,
            default: "",
        },
        editButton: {
            type: Object,
            default: {},
        },
        cssCard: {
            type: Boolean,
            default: true,
        },
        buttonsInsideDatatable: {
            type: Array,
            default: [],
        },
        id: {
            type: String,
            default: null,
        },
        filters: {
            type: Object,
            default: {},
        },
    },
    components: {
        ComponentFormDefault,
    },
    setup(props, {emit}) {
        const lengthButtons = _.values(props.buttons).length;
        const headers = ref(null);
        const allHeaders = ref({});
        const data = ref(null);
        const modal = ref();
        const modalTitle = ref("Mostrar Columnas");

        const fieldsJson = ref({});
        const dataForm = reactive({
            data: new Form({}),
        });

        onMounted(async () => {
            modal.value = new Modal("modaleditcolumn");

            $(document).on(
                "click",
                `#${props.idTable} .fa-trash`,
                function (e) {
                    if (confirm("Esta seguro que desea eliminar")) {
                        let deleteItem = deleteRowDatatable(
                            props.module,
                            $(e.target).parent().attr("id-item"),
                            data.value
                        );
                        if (deleteItem) {
                            emit("item-delete");
                        }
                    }
                }
            );

            let requestedColumns = await requestColumnsDatatableByModule(props.model);
            let showInHedaer = requestedColumns.length <= 7 ? requestedColumns.length - 1 : 7;
            let headerColumns = _.take(requestedColumns, showInHedaer);

            if (hasActionColumn(requestedColumns)) {
                headerColumns.push(getActionColumn(requestedColumns))
            }

            headers.value = headerColumns;
            allHeaders.value = await requestAllColumnsDatatableByModule(
                props.model
            );

            if (headers.value) {
                $(document).ready(function () {
                    data.value = initDatatable(
                        _.map(headers.value, (v) => v.name),
                        props.module,
                        props.idTable,
                        props.editButton,
                        props.buttonsInsideDatatable,
                        props.id,
                        props.filters,
                        allHeaders,
                        hasActionColumn(requestedColumns),
                        showInHedaer
                    );

                    emit(props.idTable, data.value);
                });
            }

            let temp = _.mapKeys(allHeaders.value, h => h.name);
            temp = _.mapValues(temp, h => {
                return {
                    value: h.user_column_datatable_module.length === 0,
                    field: h.name,
                    include: true,
                    label: h.label,
                    type: 'input-checkbox-left-order',
                }
            });

            temp.module = {
                value: props.model,
                include: false,
            };

            fieldsJson.value = temp;
            dataForm.data = new Form(fieldsJson.value);
        });

        const showAddModal = () => {
            modal.value.show();
        };

        const onSubmit = () => {
            dataForm.data
                .submit("post", "/update-column-by-user", "update")
                .then((response) => {
                });

            modal.value.hide();
            location.reload();
        };

        const updateThisField = ({field, value}) => {
            dataForm.data[field] = value;
        };

        const clearError = ({field}) => {
            dataForm.data.errors.clear(field)
        }

        const hasActionColumn = (requestedColumns) => {
            return _.filter(requestedColumns, (v) => {
                return v.label == 'Acciones';
            }).length
        }

        const getActionColumn = (requestedColumns) => {
          return _.filter(requestedColumns, (v) => {
              return v.label == 'Acciones';
          })[0]
        }

        return {
            headers,
            allHeaders,
            lengthButtons,
            showAddModal,
            onSubmit,
            modalTitle,
            fieldsJson,
            dataForm,
            updateThisField,
            clearError
        };
    },
};
</script>

<style scoped>

</style>
