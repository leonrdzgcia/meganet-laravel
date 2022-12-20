<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form
                    method="POST"
                    @submit.prevent="onSubmit"
                    @change="dataForm.data.errors.clear($event.target.name)"
                    @keydown="dataForm.data.errors.clear($event.target.name)"
                >
                    <div class="mt-3">
                        <div class="card">
                            <div class="card-header" style="background-color: #fbfaff;">
                                <div class="row customer-billing-sticky-sidebar spl-sticky-sidebar">
                                    <div class="col-lg-12 col-md-12 customer-billing-sticky-sidebar-inner ">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <ClientInfoAccountBalance
                                                        :client_id="id"></ClientInfoAccountBalance>
                                                    <div class="col-lg-5 col-md-5 col-sm-5 text-end">
                                                        <div
                                                            class="float-right customer-buttons-wrapper"

                                                        >
                                                            <div class="dropdown d-inline-block me-2">
                                                                <button type="button"
                                                                        class="btn btn-outline-secondary waves-effect waves-light"
                                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false"
                                                                >
                                                                    <span class="ms-1">Soporte</span>
                                                                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                                                                    <span class="badge bg-danger rounded-pill ms-1"
                                                                          v-text="clientTickets.open"></span>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item"
                                                                       :href="`/tickets/crear/${id}`">
                                                                        Crear Ticket</a>
                                                                    <a class="dropdown-item"
                                                                       :href="`/tickets/abiertos/${id}`">
                                                                        Abiertos <span
                                                                        class="badge bg-danger rounded-pill ms-1"
                                                                        v-text="clientTickets.open"></span></a>
                                                                    <a class="dropdown-item"
                                                                       :href="`/tickets/cerrados/${id}`">
                                                                        Cerrados <span
                                                                        class="badge bg-success rounded-pill ms-1"
                                                                        v-text="clientTickets.closed"></span></a>
                                                                </div>
                                                            </div>

                                                            <button
                                                                class="btn btn-primary"
                                                                type="submit"
                                                                :disabled="dataForm.data.errors.any()"
                                                            >
                                                                {{ submitButtonAction }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 col-xl-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Información Principal</h6>
                                        </div>
                                        <div class="p-4 m-2 h-fix-content shadow-low">
                                            <div class="form-group row">
                                                <label
                                                    class="col-sm-12 col-md-3 col-form-label text-md-end pr-2 text-sm-center">
                                                    ID:
                                                </label>
                                                <div class="col-sm-12 col-md-6 d-flex align-items-center">
                                                    {{ id }}
                                                </div>
                                            </div>

                                            <ComponentFormDefault
                                                v-for="val in fieldsJson['client_main_information']"
                                                :id="id"
                                                :json="val"
                                                :errors="dataForm.data.errors"
                                                :key="val"
                                                v-model="dataForm.data[val.field]"
                                                @update-field="updateThisField"
                                                @clear-error="clearError"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-5">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Mapa</h6>
                                        </div>
                                        <div class="col-sm-12 p-4 m-2 shadow-low">
                                            <!--                                                <google-map-->
                                            <!--                                                    :key="dataForm.data.geodata"-->
                                            <!--                                                    v-model="dataForm.data.geodata"-->
                                            <!--                                                ></google-map>-->
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Información Adicional</h6>
                                        </div>
                                        <div class="p-4 m-2 h-fix-content shadow-low">
                                            <ComponentFormDefault
                                                v-for="val in fieldsJson['client_additional_information']"
                                                :id="id"
                                                :json="val"
                                                :errors="dataForm.data.errors"
                                                :key="val"
                                                v-model="dataForm.data[val.field]"
                                                @update-field="updateThisField"
                                                @clear-error="clearError"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button
                            class="btn btn-primary"
                            type="submit"
                            :disabled="dataForm.data.errors.any()"
                        >
                            {{ submitButtonAction }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-6">
            <!--            <recent-activity lista=""></recent-activity>-->
        </div>
    </div>
</template>

<script>
import {watch, onMounted, ref} from "vue";
import {
    fieldsJson,
    dataForm,
    clearError,
    getfieldsEditedWithMultipleModel,
} from "../../../hook/crudHook";
import {getClientTickets, requestGetClientStatus} from "./helpers/helper";
import ComponentFormDefault from "../../ComponentFormDefault";
import ViewTopNameWithBalance from "./ViewTopNameWithBalance";
import ClientInfoAccountBalance from "./info/ClientInfoAccountBalance";


export default {
    name: "InformationClientCrud",
    props: {
        action: String,
        id: {
            type: String,
            default: null,
        },
    },
    components: {
        ComponentFormDefault, ViewTopNameWithBalance, ClientInfoAccountBalance
    },
    setup(props, {emit}) {
        let submitButtonAction = props.id && "Salvar Cliente";
        const clientStatus = ref({});
        const clientTickets = ref({
            open: 0,
            closed: 0
        });

        onMounted(async () => {
            if (props.id) {
                clientTickets.value = await getClientTickets(props.id);
                await getfieldsEditedWithMultipleModel(
                    [
                        {client_main_information: "ClientMainInformation"},
                        {client_additional_information: "ClientAdditionalInformation"}
                    ],
                    props.id
                );
            }
        });

        watch(
            () => dataForm.data,
            (actual, prev) => {
                if (actual)
                    emit("getTypeOfBilling", {typeOfBilling: actual.type_of_billing_id});
            }
        );

        const getClientStatus = async () => {
            return await requestGetClientStatus(props.id);
        }

        const onSubmit = async () => {
             if (await checkifClientStatusActivadoAndChangeToLocked()) {
                dataForm.data
                    .submit(
                        "post",
                        `/${"Cliente".toLowerCase()}/${props.action}`,
                        props.action
                    )
                    .then((response) =>
                        toastr.success(
                            "Cliente actualizado correctamente",
                            "Cliente"
                        )
                    );
                location.reload()
             }
        };

        const checkifClientStatusActivadoAndChangeToLocked = async () => {
          let  oldStatus = await getClientStatus();

            if (oldStatus == dataForm.data.estado) {
                return true;
            }

            if (oldStatus == 'Activo' && dataForm.data.estado == 'Bloqueado') {
                if (confirm("Estas seguro que desea Bloquear al Cliente, se detendran todos los servicios del cliente") == true) {
                    return true;
                } else {
                    return false;
                }
            }
            if (oldStatus == 'Bloqueado' && dataForm.data.estado == 'Activo') {
                if (confirm("Estas seguro que desea Activar al Cliente, se Activaran todos los servicios del cliente") == true) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        const updateThisField = ({field, value}) => {
            if (field == "type_of_billing_id") emit("getTypeOfBilling", {typeOfBilling: value.value});
            dataForm.data[field] = value;
        };

        return {
            fieldsJson,
            dataForm,
            onSubmit,
            clearError,
            updateThisField,
            submitButtonAction,
            clientTickets
        };
    },
};
</script>

<style scoped></style>
