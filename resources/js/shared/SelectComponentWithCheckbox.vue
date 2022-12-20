<template>
    <div :class="`row mb-2 ${errors.has(property.field) && 'has-danger'}`">
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
                multiple
            >
                <option
                    v-for="option in options.val"
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
import {onMounted, reactive, ref, watch} from "vue";
import {getOptions, getOptionsWithoutId, selectTransform, convertToBoostrapSelect} from "../helpers/Transform";

export default {
    name: "SelectComponentWithCheckbox",
    props: {
        errors: {
            type: Object,
            default: {},
        },
        property: Object,
        modelValue: {
            type: Array,
            default: []
        },
        id: {
            type: String,
            default: null
        }
    },
    setup(props,{emit}){
        const val = ref(props.modelValue);
        const options = reactive({
            val: [],
        });

        watch(val,()=>{
            emit('update-field',{'value': val,'field': props.property.field})
        });

        onMounted(async () => {
            options.val = props.property.options ? selectTransform(props.property.options)
                : (props.id ?
                    await getOptionsWithoutId(props.property.search, props.id) :
                    await getOptions(props.property.search))

            if (options.val.length){
                $(document).ready(function (){
                    convertToBoostrapSelect(props.property.field, val, options.val);
                })
            }
        })


        return {
            val,
            options
        }
    }
}
</script>

<style scoped></style>
