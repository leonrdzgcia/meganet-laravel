<template>
    <div>
        <div class="d-flex justify-content-between">
            <div><h6>CONFIGURACIÓN DE FACTURACIÓN</h6></div>
            <div>
                <h5><i class="bx bxs-save cursor-pointer" @click="onSubmit"></i></h5>
            </div>
        </div>

        <hr class="mb-2" />
        <div v-for="val in fieldsJson">
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

        <div v-if="typeOfBilling == 1">
            <hr class="mb-2" />
            <div class="row">
                <button type="button" class="btn btn-success waves-effect waves-light" :onClick="toogleMiniCalendar">
                    <i class="fas fa-calendar-check font-size-16 align-middle me-2"></i> {{ showMiniCalendar ? 'Ocultar' : 'Mostrar' }} Calendario
                </button>
            </div>
            <div class="row">
                <Minifullcalend :data="dataForm.data" :showMiniCalendar="showMiniCalendar"></Minifullcalend>
            </div>
        </div>
    </div>
</template>

<script>
import Minifullcalend from "./components/Minifullcalend";
import ComponentFormDefault from "../../../ComponentFormDefault";
import { onMounted, reactive, ref, watch } from "vue";
import Form from "../../../../helpers/Form";
import { requestFieldsByModuleIdRelation } from "../../../../helpers/Request";

export default {
    name: "ClientBillingConfiguration",
    props: {
        id: String,
        typeOfBilling: String,
    },
    components: { ComponentFormDefault, Minifullcalend },
    setup(props) {
        const fieldsJson = ref({});
        const dataForm = reactive({
            data: new Form({}),
        });
        const showMiniCalendar = ref(true);

        onMounted( () => {
            initComponent(props.typeOfBilling);
        });

        watch(
            () => props.typeOfBilling,
            (type, typeBefore) => {
                initComponent(type);
            }
        );

        const initComponent = async (typeOfBilling) => {
            typeOfBilling == 1
                ? await getfieldsJsonRelation(
                      "ClientBillingConfigurationRecurrent",
                      "Client",
                      props.id,
                      "billing_configuration"
                  )
                : await getfieldsJsonRelation(
                      "ClientBillingConfigurationCustom",
                      "Client",
                      props.id,
                      "billing_configuration"
                  );
            dataForm.data.type_billing_id = typeOfBilling;
        };

        const getfieldsJsonRelation = async (
            module,
            parent_module,
            id,
            relation
        ) => {
            fieldsJson.value = await requestFieldsByModuleIdRelation(
                module,
                parent_module,
                id,
                relation
            );
            dataForm.data = new Form(fieldsJson.value);
        };

        const onSubmit = () => {
            dataForm.data.originalData.type_billing_id = null;
            dataForm.data
                .submit("post", `/cliente/billing/update-billing-configuration/${props.id}`, "update")
                .then((response) =>
                    toastr.success(
                        "CONFIGURACIÓN DE FACTURACIÓN actualizado satisfactoriamente",
                        "FACTURACIÓN"
                    )
                );
        };

        const updateThisField = ({ field, value }) => {
            dataForm.data[field] = value;
        };

        const clearError = ({ field }) => {
            dataForm.data.errors.clear(field);
        };

        const toogleMiniCalendar = () => {
            showMiniCalendar.value = !(showMiniCalendar.value);
        }

        return {
            fieldsJson,
            dataForm,
            onSubmit,
            clearError,
            updateThisField,
            showMiniCalendar,
            toogleMiniCalendar
        };
    },
};
</script>

<style scoped></style>
