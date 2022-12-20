<template>
    <div
        class="modal fade"
        id="modalpayment"
        data-backdrop="static"
        data-keyboard="false"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="m-auto">{{ title }}</h6>
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

                        <div class="alert alert-info alert-dismissible fade show mb-2" role="alert" v-if="costAllServiceActive">
                            <strong>Costo de los Servicios activos: </strong> {{ costAllServiceActive }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show mb-2" role="alert">
                            <strong>Vencimiento del Servicio</strong> - {{ activeServiceExpiration }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div v-for="val in fieldsJson">
                            <ComponentFormDefault
                                v-if="val.include"
                                :id="idClient"
                                :json="val"
                                :errors="dataForm.data.errors"
                                :key="val"
                                v-model="dataForm.data[val.field]"
                                @update-field="updateThisField"
                                @clear-error="clearError"
                            />
                        </div>
                        <div v-show="showImage" class="image resize">
                            <img style="width: -webkit-fill-available;" :src="`${getShowImage}`" :data-zoom="`${getShowImage}`" @dblclick="changeDrift">
                            <p></p>
                        </div>

                        <div class="form-group text-center">
                            <a class="btn btn-secondary me-3"
                               href="javascript:void(0)"
                               @click="closeModal"
                            >
                                Cerrar
                            </a>
                            <button
                                class="btn btn-primary"
                                type="submit"
                                :disabled="dataForm.data.errors.any() || disabledButton"
                            >
                                Aplicar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref, watch, onMounted } from "vue";
import {getActiveServiceExpiration, getCostAllServiceActive} from "../helpers/request";
import ComponentFormDefault from "../../../../ComponentFormDefault";
import {requestEditedFieldsById, requestFieldsByModule} from "../../../../../helpers/Request";
import Form from "../../../../../helpers/Form";
import Drift from 'drift-zoom';

export default {
    name: "ClientCrudPayment",
    props: {
        module: {
            type: String,
            default: null,
        },
        idClient: {
            type: String,
            default: null,
        },
        action: String,
    },
    components: { ComponentFormDefault },
    setup(props, { emit }) {
        const title = ref("Crear Pago");
        const fieldsJson = ref({});
        const dataForm = reactive({
            data: new Form({}),
        });
        const costAllServiceActive = ref(0);
        const activeServiceExpiration = ref(0);
        const showImage = ref(false);
        const getShowImage = ref('');
        const drift = ref();
        const driftDisbled = ref(true);
        const disabledButton = ref(false);

        onMounted(() => {
            drift.value = new Drift($('div.image > img')[0], {
                paneContainer: $('div.image > p')[0]
            });
            drift.value.disable()
            initComponent(props.action);
        });

        const changeDrift = () => {
            driftDisbled.value = !(driftDisbled.value);
            driftDisbled.value ? drift.value.disable() : drift.value.enable();
        }

        watch(
            () => props.action,
            (action, actionBefore) => {
                initComponent(action);
            }
        );

        watch(dataForm, () => {
            if(dataForm.data.file){
                if(dataForm.data.file.type == "png" || dataForm.data.file.type == "jpg" || dataForm.data.file.type == "jpeg"){
                    showImage.value = true;
                    getShowImage.value = dataForm.data.file.path
                }else if ($('input[name="file"]').length){
                    let type = $('input[name="file"]')[0].files[0].type;
                    if (type.includes('jpg') || type.includes('png') || type.includes('jpeg')){
                        showImage.value = true;
                        getShowImage.value = URL.createObjectURL($('input[name="file"]')[0].files[0])
                    }else{
                        showImage.value = false;
                    }
                }
            }else{
                showImage.value = false;
            }
        });

        const initComponent = async (action) => {
            costAllServiceActive.value = await getCostAllServiceActive(props.idClient);
            activeServiceExpiration.value = await getActiveServiceExpiration(props.idClient);

            action == `crear/${props.idClient}`
                ? await getfieldsJson("ClientPayment")
                : await getfieldsEdited(
                      "ClientPayment",
                      props.action
                  );
        };

        const getfieldsEdited = async (model, action) => {
            let id = action.substr(7);
            fieldsJson.value = await requestEditedFieldsById(model, id);
            dataForm.data = new Form(fieldsJson.value);
            title.value = "Editar Gasto";
        };

        const getfieldsJson = async (model) => {
            fieldsJson.value = await requestFieldsByModule(model);
            dataForm.data = new Form(fieldsJson.value);
            title.value = "Crear Gasto";
        };

        const onSubmit = () => {
            disabledButton.value = true;
            dataForm.data
                .uploadFile(
                    `/cliente/billing/payment/${props.action}`,
                    title.value == 'Crear Gasto' ? 'reset' : 'editar'
                )

                .then((response) => {
                    toastr.success(
                        "Pago"
                    );
                    initComponent(props.action);
                    emit("cleanModal");
                    disabledButton.value = true;
                    location.reload();
                });
        };

        const closeModal = () => {
            emit("cleanModal");
        };

        const updateThisField = ({ field, value }) => {
            dataForm.data[field] = value;
            disabledButton.value = false;
        };

        const clearError = ({field}) => {
            dataForm.data.errors.clear(field)
        }

        return {
            fieldsJson,
            dataForm,
            onSubmit,
            updateThisField,
            clearError,
            closeModal,
            title,
            costAllServiceActive,
            activeServiceExpiration,
            showImage,
            getShowImage,
            changeDrift,
            disabledButton
        };
    },
};
</script>

<style scoped></style>
