<template>
    <div :class="`row mb-2 item align-items-center ${errors.has(property.field) && 'has-danger'}`">
        <label
            :for="property.field"
            :class="`col-sm-12 col-md-3 col-form-label text-md-end pr-2 text-sm-center`"
        >
            {{ property.label }}
        </label>
        <div class="col-sm-12 col-md-9">
            <input
                type="checkbox"
                :id="property.field"
                switch="none"
                v-model="val"
            />
            <label class="m-0" :for="property.field"></label>
            <div v-if="errors.has(property.field)" class="pristine-error text-help">
                {{ errors.get(property.field) }}
            </div>
        </div>
    </div>
</template>

<script>
import {watch, ref, onMounted} from "vue";

export default {
    name: "InputCheckbox",
    props: {
        errors: {
            type: Object,
            default: {},
        },
        property: Object,
        modelValue: Boolean | Number,
    },
    setup(props, { emit }) {
        const val = ref(false);

        onMounted(async () => {
            val.value  = props.modelValue  ? !!_.toInteger(props.modelValue) : await getValByDefaultValue();
            emit("update-field", {
                value: val,
                field: props.property.field,
            });
        });

        watch(val, () => {
            emit("update-field", { value: val, field: props.property.field });
        });

        const getValByDefaultValue = () => {
            return props.property.default_value ? !!_.toInteger(props.property.default_value) : false;
        }

        return {
            val,
        };
    },
};
</script>

<style scoped></style>
