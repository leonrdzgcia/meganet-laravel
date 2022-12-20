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
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <!-- <p>Usuario(0001user)</p> -->
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-info me-2" :disabled="disabled" @click="convertToClient(id)">
                                Convertir
                            </button>
                            <button type="button" class="btn btn-outline-info" @click.prevent="onSubmit">
                                Salvar
                            </button>
                        </div>
                        <!-- End Example split danger button -->
                    </div>

                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-xl-12 col-xxl-6">
                                        <div class="p-4 m-2 border h-fix-content shadow-low">
                                            <h6>Informacion Principal</h6>
                                            <hr class="mb-4"/>

                                            <div class="form-group row">
                                                <label class="col-sm-12 col-md-3 col-form-label text-md-end pr-2 text-sm-center">
                                                    ID:
                                                </label>
                                                <div class="col-sm-12 col-md-9 col-form-label text-md-start pr-2 text-sm-center">
                                                    {{ id }}
                                                </div>
                                            </div>

                                            <div v-for="val in fieldsJson['main_information']">
                                                <ComponentFormDefault
                                                    v-if="val.include"
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
                                    <div class="col-xl-12 col-xxl-5">
                                        <div class="row">
                                            <div class="col-xl-12 p-4 m-2 border shadow-low">
                                                <h6>Informacion del Cliente</h6>
                                                <hr class="mb-4"/>

                                                <div v-for="val in fieldsJson['lead_information']">
                                                    <ComponentFormDefault
                                                        v-if="val.include"
                                                        :id="id"
                                                        :json="val"
                                                        :errors="dataForm.data.errors"
                                                        :key="val"
                                                        v-model="dataForm.data[val.field]"
                                                        @update-field="updateThisField"
                                                        @clear-error="clearError"
                                                    />
                                                </div>

                                                <NotificationCrm
                                                    :id="id"
                                                    :email="dataForm.data['email']"
                                                    @reset="reloadComponent+=1"
                                                    :key="reloadComponent"
                                                />
                                            </div>
                                        </div>
                                        <div class="row" v-if="hasPermission.data.canView('geolocation_crm')">
                                            <div class="col-xl-12 p-4 m-2 border shadow-low">
                                                <h6>Mapa</h6>
                                                <br/>
                                                <!--                                                <InputModalWithGoogleMap-->
                                                <!--                                                    :position="-->
                                                <!--                                                        dataForm.data.geodata-->
                                                <!--                                                    "-->
                                                <!--                                                />-->
                                                <br/>
                                                <hr class="mb-4"/>
                                                <!--                                                <GoogleMap-->
                                                <!--                                                    :key="dataForm.data.geodata"-->
                                                <!--                                                    v-model="-->
                                                <!--                                                        dataForm.data.geodata-->
                                                <!--                                                    "-->
                                                <!--                                                />-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center mb-1">
                        <a class="btn btn-secondary me-2" href="/crm">
                            Atras
                        </a>
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
        <div class="row">
            <CrmRecentActivity
                :id="id"
                @show-information="showInformation"
            ></CrmRecentActivity>
        </div>
    </div>

    <div class="modal fade modal-center modal-activity" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="m-1 p-2" v-for="(text,key) in textInformation">
                        <div class="col-12 text-center">
                            <strong>{{key}}:</strong>
                        </div>
                        <div class="col-12 overflow-auto" v-html="JSON.stringify(text)">
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script>
import {onBeforeMount, reactive, ref} from "vue";
import {
    fieldsJson,
    dataForm,
    clearError,
    updateThisField,
    getfieldsEditedWithMultipleModel,
} from "../../../hook/crudHook";
import ComponentFormDefault from "../../ComponentFormDefault";
import InputModalWithGoogleMap from "../../../shared/InputModalWithGoogleMap";
import GoogleMap from "../../base/googlemap/GoogleMap";
import Permission from "../../../helpers/Permission";
import NotificationCrm from "./components/NotificationCrm";
import CrmRecentActivity from "./components/CrmRecentActivity";
import {allViewHasPermission} from "../../../helpers/Request";
import axios from "axios";
import {updateLastContacted} from "./helpers/helper";

export default {
    name: "InformationCrmCrud",
    props: {
        action: String,
        id: {
            type: String,
            default: null,
        },
    },
    components: {
        ComponentFormDefault,
        GoogleMap,
        InputModalWithGoogleMap,
        NotificationCrm,
        CrmRecentActivity
    },
    setup(props) {
        let submitButtonAction = "Salvar CRM";
        const reloadComponent = ref(0);
        const disabled = ref(false);
        const hasPermission = reactive({
            data: new Permission({})
        })
        const textInformation = ref('');

        onBeforeMount(async () => {
            if (props.id) {
                hasPermission.data = new Permission(await allViewHasPermission());
                await getfieldsEditedWithMultipleModel(
                    [
                        {main_information: "CrmMainInformation"},
                        {lead_information: "CrmLeadInformation"},
                    ],
                    props.id
                );
                updateLastContacted(props.id);
            }
        });

        const onSubmit = () => {
            dataForm.data
                .submit("post", `/crm/${props.action}`, props.action)
                .then((response) =>
                    toastr.success(
                        "Crm Actualizado Satisfactoriamente",
                        "Crm"
                    )
                );
        };

        const convertToClient = async (crmId) => {
            disabled.value = true;
            await axios["post"](`/crm/convert-to-client/${crmId}`, {}).then((response) => {
                toastr.success("Crm Convertido a Cliente Satisfactoriamente")
                setTimeout(() => {
                    location.href = `/cliente/editar/${response.data}`
                },1000)
            });
            disabled.value = false;
        }

        const showInformation = (info) => {
            console.log(info)
            textInformation.value = info
        }

        return {
            fieldsJson,
            dataForm,
            onSubmit,
            clearError,
            updateThisField,
            submitButtonAction,
            hasPermission,
            reloadComponent,
            convertToClient,
            disabled,
            textInformation,
            showInformation
        };
    },
};
</script>

<style scoped></style>
