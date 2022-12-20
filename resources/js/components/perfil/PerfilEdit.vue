<template>
    <div class="col-xl-12">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-center">
                    <div class="clearfix"></div>
                    <div>
                        <img
                            :src="!perfilJson ? 'http://localhost:8000/assets/images/users/avatar-1.jpg' : perfilJson.image_profile"
                            alt=""
                            class="
                                avatar-lg
                                rounded-circle
                                img-thumbnail
                                cursor-pointer
                            "
                            @click="changeImage"
                            accept="image/*"
                            cond="image"
                        />
                    </div>
                    <h5 class="mt-3 mb-1">{{ perfilRelationJson.name }}</h5>
                    <p class="text-muted">{{ !perfilJson ? '..' : perfilJson.rol_name }}</p>
                </div>
                <hr class="my-4"/>
                <form
                    method="POST"
                    @submit.prevent="onSubmit"
                    @change="dataForm.data.errors.clear($event.target.name)"
                    @keydown="
                            dataForm.data.errors.clear($event.target.name)
                        "
                >
                    <hr class="mb-5"/>

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
                        <a class="btn btn-secondary me-2" :href="`/perfil/${id}`">
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
    </div>
</template>

<script>
import {clearError, dataForm, fieldsJson, getfieldsEdited, updateThisField} from "../../hook/crudHook";
import {onMounted, ref} from "vue";
import ComponentFormDefault from "../ComponentFormDefault";
import {requestPerfilJson} from "./helper/request";

export default {
    name: "PerfilEdit",
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
        let submitButtonAction = "Salvar Perfil";
        const perfilRelationJson = ref({});
        const perfilJson = ref({});

        onMounted(() => {
            initComponent();
            getPerfilJson();
        });

        const initComponent = async () => {
            await getfieldsEdited("Perfil", props.id);
        }

        const getPerfilJson = async () => {
            perfilRelationJson.value = await requestPerfilJson(props.id);
            perfilJson.value = !perfilRelationJson.value.perfil ? null : perfilRelationJson.value.perfil;

        }


        const onSubmit = () => {
            dataForm.data
                .submit(
                    "post",
                    `/perfil/${props.action}`,
                    props.action
                )
             .then((response) => location.href = '/perfil/' + props.id);
        };

        return {
            fieldsJson,
            dataForm,
            onSubmit,
            clearError,
            updateThisField,
            submitButtonAction,
            perfilRelationJson,
            perfilJson
        };
    }
}
</script>

<style scoped></style>
