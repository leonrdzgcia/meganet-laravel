
<template>
    <div class="form-group row">
        <label :for="field" class="col-sm-12 col-md-3 col-form-label text-sm-center text-md-end">{{  label }}</label>
        <div class="col-sm-12 col-md-8">
            <div>
                <select
                    :class="{'form-control': true, 'parsley-error': hasError }"
                    :id="field"
                    :name="field"
                    :disabled="false"
                    v-model="val"
                >
                    <option value=null :text="placeholder"></option>
                    <option v-for="option in options.val" :value="option.value" :text="option.text"></option>
                </select>
            </div>

            <ul v-if="hasError" class="parsley-errors-list filled" aria-hidden="false">
                <li class="parsley-required" v-text="error"></li>
            </ul>
        </div>
    </div>
</template>

<script>

import {reactive, ref, watch, onMounted} from "vue";
import {selectTransform, getOptions} from "../helpers/Transform"

export default {
    name: "SelectMultipleAddComponent",
    props: {
        label: String,
        field: String,
        error: String,
        placeholder: {
            type: String,
            default: ''
        },
        hasError: {
            type: Boolean,
            default: false
        },
        options: {
            type: Object,
            default: []
        },
        modelValue: {
            type: String,
            default: null
        },
    },
    setup(props,{emit}){

        const val = ref(props.modelValue);
        const options = reactive({
            val: [],
        });

        watch(val,()=>{
            emit('update-field',{'value': val,'field': props.field})
        })

        onMounted(async () => {
            options.val = props.options.options ? selectTransform(props.options.options)
                : await getOptions(props.options.search)
        })

        return {
            val,
            options
        }
    }
}
</script>

<style scoped>

</style>
