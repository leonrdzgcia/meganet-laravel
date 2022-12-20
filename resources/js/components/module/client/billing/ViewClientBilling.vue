<template>
    <div class="p-5">
        <div class="row mt-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <ClientInfoAccountBalance
                            :client_id="id">
                        </ClientInfoAccountBalance>
                        <div class="col-lg-7 col-md-7 col-sm-7 align-self-center">
                            <b class="customer-name-wrapper me-1">Costo por mes de servicio:</b>
                            <span class="customer-billing-balance-title">$ {{ data.cost_for_month }}</span><br>
                            <b class="customer-name-wrapper me-1" v-if="!data.isRecurrent && !data.expired">Costo de
                                ({{ data.days_left }} días) faltantes de servicio:</b>
                            <span class="customer-billing-balance-title"
                                  v-if="!data.isRecurrent && !data.expired">$ {{ data.cost_per_days_service }}</span><br
                            v-if="!data.isRecurrent && !data.expired">
                            <b class="customer-name-wrapper me-1">Fecha de suspención:</b>
                            <span class="customer-billing-balance-title bg-danger fw-bold text-white"
                                  v-if="data.expired">{{ data.expiration_date }}</span>
                            <span class="customer-billing-balance-title" v-if="!data.expired">{{
                                    data.expiration_date
                                }}</span>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 text-end">
                            <div
                                class="float-right customer-buttons-wrapper"
                            >
                                <button
                                    class="btn btn-primary"
                                    @click="showAddModal"
                                    type="button"
                                    v-if="data.balance < 0"
                                >
                                    Negociaciones
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="
                    col-md-12 col-xl-6
                    p-4
                    ml-xl-5
                    border
                    h-fix-content
                    shadow-low
                "
            >
                <ClientBillingConfiguration
                    :id="id"
                    :typeOfBilling="typeOfBilling"
                />
            </div>

            <div class="col-md-12 col-xl-5 mt-md-3 mt-xl-0">
                <ClientPaymentAccount :id="id"/>
                <br/>
                <ClientBillingAddress :id="id"/>
                <br/>
                <ClientRemindersConfiguration
                    v-if="isRecurrentTypeOfBilling"
                    :id="id"
                />
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modalAgreement"
        data-backdrop="static"
        data-keyboard="false"
    >
        <div class="modal-dialog modal-lg">
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
                        <div class="alert alert-info alert-dismissible fade show mb-2" role="alert" v-if="clientDebit">
                            <strong>Débito : </strong> {{ clientDebit }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

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

                        <div class="alert alert-info alert-dismissible fade show mb-2" role="alert" v-if="clientDebit">
                            <strong>Descuendo del {{ porcent }}% a pagar:  </strong> {{ clientPay }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="form-group text-center">
                            <button
                                type="button"
                                class="btn btn-light waves-effect me-2"
                                data-bs-dismiss="modal"
                            >
                                Cerrar
                            </button>
                            <button
                                class="btn btn-primary"
                                type="submit"
                                :disabled= "disableSubmit"
                            >
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
import ClientBillingConfiguration from "./ClientBillingConfiguration";
import ClientPaymentAccount from "./ClientPaymentAccount";
import ClientBillingAddress from "./ClientBillingAddress";
import ClientRemindersConfiguration from "./ClientRemindersConfiguration";
import ClientInfoAccountBalance from "../info/ClientInfoAccountBalance"
import ComponentFormDefault from "../../../../components/ComponentFormDefault";

import {onMounted, reactive, ref, watch} from "vue";
import {requestBillingInformationBlock, getClientDebit, requestPaymentMethod} from "./helpers/request";
import Form from "../../../../helpers/Form";
import Modal from "../../../../helpers/modal";
import {requestFieldsByModule} from "../../../../helpers/Request";

export default {
    name: "ViewClientBilling",
    components: {
        ClientRemindersConfiguration,
        ClientBillingAddress,
        ClientPaymentAccount,
        ClientBillingConfiguration,
        ClientInfoAccountBalance,
        ComponentFormDefault,
        requestPaymentMethod
    },
    props: {
        id: String,
        typeOfBilling: String,
    },
    setup(props) {
        const isRecurrentTypeOfBilling = ref(props.typeOfBilling == 1);
        const data = ref({});
        const modal = ref();
        const modalTitle = ref("Acuerdo de Rectificación de Débito");
        const clientDebit = ref(0);
        const clientDebitCalc = ref(0);
        const disableSubmit = ref(true);
        const clientPay = ref(0);
        const payment_method =  ref({});
        const porcent = ref(0);
        const fieldsJson = ref({});
        const dataForm = reactive({
            data: new Form({}),
        });

        onMounted(async () => {
            getBillingInformationBlock();
            clientDebit.value = await getClientDebit(props.id);
            modal.value = new Modal("modalAgreement");
            fieldsJson.value = await requestFieldsByModule('ClientDebitRectificationAgreement');
            dataForm.data = new Form(fieldsJson.value);
            disableSubmit.value = true;
            porcent.value = 0;
            clientDebitCalc.value = 0;

        });

        const showAddModal = async () => {
            modal.value.show();
        };

        const getBillingInformationBlock = async () => {
            data.value = await requestBillingInformationBlock(props.id);
        };

        watch(
            () => props.typeOfBilling,
            (typeOfBilling, typeBefore) => {
                isRecurrentTypeOfBilling.value = typeOfBilling == 1;
            }
        );

        const updateThisField = async ({field, value}) => {

            if(field == 'apply_group_of_months') {
                porcent.value = value.value;
                porcent.value = '0.'+porcent.value;
                clientDebitCalc.value = clientDebit.value * -1 ;
                if ( porcent.value ){
                    clientPay.value =  (clientDebitCalc.value - (clientDebitCalc.value * porcent.value)).toFixed(2);
                }
            }

            if(field == 'payment_method_id' ) {
                payment_method.value = await requestPaymentMethod(value.value);
                if (payment_method.value.type == 'Acuerdo de Pago') {
                    disableSubmit.value = false;
                } else {
                    disableSubmit.value = true;
                }
            }
            dataForm.data[field] = value;
        }

        const clearError = ({field}) => {
            dataForm.data.errors.clear(field)
        }

        const onSubmit = () => {
            dataForm.data
                .submit("post", `/cliente/billing/client-debit-rectification-agreement/${props.id}`, "update")
                .then((response) => {
                });

            modal.value.hide();
            location.reload();
        };

        return {
            isRecurrentTypeOfBilling,
            clientDebit,
            porcent,
            clientPay,
            disableSubmit,
            data,
            showAddModal,
            modalTitle,
            fieldsJson,
            dataForm,
            updateThisField,
            clearError,
            onSubmit
        };
    },
};
</script>

<style scoped></style>
