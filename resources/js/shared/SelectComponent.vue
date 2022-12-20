<template>
    <div :class="`row mb-2 ${errors.has(property.field) && 'has-danger'}`" :key="opts">
        <label
            :for="property.field"
            :class="`col-sm-12 col-md-3 col-form-label text-md-end pr-2 text-sm-center`"
        >
            {{ property.label }}
        </label>
        <div class="col-sm-12 col-md-9">
            <select
                :class="{'form-control': true}"
                :name="property.field"
                :disabled="property.disabled"
                v-model="val"
            >
                <option value=null :text="property.placeholder"></option>
                <option
                    v-for="option in opts"
                    :value="option.value"
                    :text="option.text"
                ></option>
            </select>
            <div v-if="errors.has(property.field)" class="pristine-error text-help">
                {{ errors.get(property.field) }}
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref, watch, onMounted } from "vue";
import {selectTransform, getOptions, convertToSelect2} from "../helpers/Transform";
import {getUserAuthenticated} from "../helpers/Request";

export default {
    name: "SelectComponent",
    props: {
        errors: {
            type: Object,
            default: {},
        },
        property: Object,
        modelValue: {
            type: String,
            default: null,
        },
    },
    setup(props, { emit }) {
        const val = ref(props.modelValue);
        const options = ref([]);
        const opts = reactive(options);

        watch(val, () => {
            emit("update-field", { value: val, field: props.property.field });
        });

        onMounted(async () => {
            if (props.property.default_value == 'user_authenticated'){
                props.property.default_value = await getUserAuthenticated();
            }

            val.value = props.modelValue ?? (props.property.default_value ?? null);

            options.value = props.property.options
                ? selectTransform(props.property.options)
                : await getOptions(props.property.search);
        });

        return {
            val,
            opts,
        };
    },
};
</script>

<style scoped></style>
