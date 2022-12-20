<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Custom</h4>
                    <form
                        method="POST"
                        @submit.prevent="onSubmit"
                        @change="dataForm.data.errors.clear($event.target.name)"
                        @keydown="
                            dataForm.data.errors.clear($event.target.name)
                        "
                    >
                        <hr class="mb-5" />
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

                        <div class="form-group text-center">
                            <a class="btn btn-secondary me-2" href="/custom">
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
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
</template>

<script>
import { onMounted } from "vue";
import {
    getfieldsJson,
    getfieldsEdited,
    updateThisField,
    clearError,
    fieldsJson,
    dataForm,
} from "../../../hook/crudHook";
import ComponentFormDefault from "../../ComponentFormDefault";

export default {
    name: "CustomCrud",
    props: {
        action: String,
        id: {
            type: String,
            default: null,
        },
    },
    components: {
        ComponentFormDefault,
    },
    setup(props) {
        let submitButtonAction = props.id
            ? "Salvar Plan Custom"
            : "Crear Plan Custom";

        onMounted(async () => {
            props.id
                ? await getfieldsEdited("Custom", props.id)
                : await getfieldsJson("Custom");
        });

        const onSubmit = () => {
            dataForm.data
                .submit(
                    "post",
                    `/${"custom".toLowerCase()}/${props.action}`,
                    props.action
                )
                .then((response) => location.href = '/custom/success/' + props.id ?? null);
        };

        return {
            fieldsJson,
            dataForm,
            onSubmit,
            clearError,
            updateThisField,
            submitButtonAction,
        };
    },
};
</script>

<style scoped></style>
