<template>
    <div :class="`row mb-2 ${errors.has(property.field) && 'has-danger'}`">
        <label
            :for="property.field"
            :class="`d-sm-block d-md-none col-sm-12 col-form-label pe-2`"
        >
            {{ property.label }}
        </label>

        <div class="input-group">
            <label
                :for="property.field"
                :class="`d-md-block d-none col-md-3 col-form-label text-md-end pe-2 `"
            >
                {{ property.label }}
            </label>
            <div class="ms-1 input-group-text">
                <span
                    class="text-black cursor-pointer"
                    :id="`${property.field}1`"
                    @click="viewPassword"
                >
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <input
                :type="typeInput"
                :name="property.field"
                :placeholder="property.placeholder"
                :aria-label="property.placeholder"
                :aria-describedby="`${property.field}1`"
                :class="'form-control col-sm-12 col-md-9'"
                v-model="val"
                :disabled="property.disabled"
                autocomplete="off"
            />
            <div class="input-group-text">
                <span
                    class="text-black cursor-pointer"
                    :id="`${property.field}1`"
                    @click="generateRandomPassword"
                >
                    refresh
                </span>
            </div>
        </div>
        <span class="col-sm-12 col-md-3"></span>
        <div v-if="errors.has(property.field)" class="col-sm-9 pristine-error text-help">
            {{ errors.get(property.field) }}
        </div>
    </div>
</template>

<script>
import {onMounted, ref, watch} from "vue";
import {requestRandomPassword} from "../helpers/Request";

export default {
    name: "InputPassword",
    props: {
        errors: {
            type: Object,
            default: {},
        },
        property: Object,
        modelValue: String,
    },
    setup(props, {emit}) {
        const val = ref(props.modelValue);
        const typeInput = ref(null);

        onMounted(() => {
            typeInput.value = "password";
        });

        const viewPassword = () => {
            typeInput.value == "password"
                ? (typeInput.value = "text")
                : (typeInput.value = "password");
        };

        const generateRandomPassword = async () => {
            val.value = await requestRandomPassword();
        };

        watch(val, () => {
            emit("update-field", {value: val, field: props.property.field});
        });

        return {
            val,
            typeInput,
            viewPassword,
            generateRandomPassword,
        };
    },
};
</script>

<style scoped></style>
