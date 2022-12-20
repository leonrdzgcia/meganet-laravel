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
                :id="property.field"
                :disabled="property.disabled"
                v-model="val"
            >
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

export default {
    name: "Select2Component",
    props: {
        errors: {
            type: Object,
            default: {},
        },
        property: Object,
        idModel: {
            type: String,
            default: null,
        },
        modelValue: {
            type: String,
            default: null,
        },
    },
    setup(props, { emit }) {
        const val = ref(props.modelValue);
        const options = ref([]);
        const opts = reactive(options);
        const choice = ref();
        const idMod = ref(props.idModel);

        watch(val, () => {
            emit("update-field", { value: val, field: props.property.field });
        });

        watch(
            () => props.idModel,
            (actual, actionBefore) => {
                idMod.value = actual;
            }
        );

        watch(
            () => props.modelValue,
            (actual, actionBefore) => {
                if (choice.value) choice.value.setChoiceByValue(actual)
            }
        );

        onMounted(async () => {
            options.value = props.property.options
                ? selectTransform(props.property.options)
                : await getOptions(props.property.search, idMod.value);

            $(document).ready(async () => {
                choice.value = await convertToSelect2(props.property.field, options, props.modelValue, props.property.placeholder);
            });
        });

        return {
            val,
            opts,
        };
    },
};
</script>

<style scoped></style>
