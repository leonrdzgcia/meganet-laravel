<template>
    <div :class="`row mb-2 ${errors.has('state_id') && 'has-danger'}`">
        <label
            for="state_id"
            :class="`col-sm-12 col-md-3 col-form-label text-md-end pr-2 text-sm-center`"
        >
            Estado
        </label>
        <div class="col-sm-12 col-md-9">
            <select
                :class="{'form-control': true}"
                name="state_id"
                id="state_id"
                v-model="valState"
            >
            </select>
            <div v-if="errors.has('state_id')" class="pristine-error text-help">
                {{ errors.get('state_id') }}
            </div>
        </div>
    </div>

    <div :class="`row mb-2 ${errors.has('municipality_id') && 'has-danger'}`">
        <label
            for="municipality_id"
            :class="`col-sm-12 col-md-3 col-form-label text-md-end pr-2 text-sm-center`"
        >
            Municipio
        </label>
        <div class="col-sm-12 col-md-9">
            <select
                :class="{'form-control': true}"
                name="municipality_id"
                id="municipality_id"
                v-model="valMunicipio"
            >
            </select>
            <div v-if="errors.has('municipality_id')" class="pristine-error text-help">
                {{ errors.get('municipality_id') }}
            </div>
        </div>
    </div>

    <div :class="`row mb-2 ${errors.has('colony_id') && 'has-danger'}`">
        <label
            for="colony_id"
            :class="`col-sm-12 col-md-3 col-form-label text-md-end pr-2 text-sm-center`"
        >
            Colonia
        </label>
        <div class="col-sm-12 col-md-9">
            <select
                :class="{'form-control': true}"
                name="colony_id"
                id="colony_id"
                v-model="valColony"
            >
            </select>
            <div v-if="errors.has('colony_id')" class="pristine-error text-help">
                {{ errors.get('colony_id') }}
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, reactive, ref, watch} from "vue";
import {convertToSelect2, getOptions, selectTransform} from "../helpers/Transform";
import {getValueDB} from "../helpers/Request";

export default {
    name: "Select2EstadoMunicipioColoniaComponent",
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
    setup(props, {emit}) {

        const select2 = ref({
            'state': {},
            'municipio': {},
            'colony': {}
        });

        const valuesDb = ref({
            state_id: '',
            municipality_id: '',
            colony_id: ''
        });

        const valState = ref();
        const valMunicipio = ref();
        const valColony = ref();

        const optionsState = ref([]);
        const optsState = reactive(optionsState);

        const optionsMunicipio = ref([]);
        const optsMunicipio = reactive(optionsMunicipio);

        const optionsColony = ref([]);
        const optsColony = reactive(optionsColony);

        watch(valState, () => {
            emit("update-field", {value: valState, field: "state_id"});

            changeMunicipio(valState.value);
            changeColony();
        });
        watch(valMunicipio, () => {
            emit("update-field", {value: valMunicipio, field: "municipality_id"});
            changeColony(valMunicipio.value);
        });
        watch(valColony, () => {
            emit("update-field", {value: valColony, field: "colony_id"});
        });

        onMounted(async () => {
            optionsState.value = await getOptions({
                'model': 'App\\Models\\State',
                'id': 'id',
                'text': 'name'
            });
            await changeMunicipio();
            await changeColony();

            if (props.modelValue) {
                let response = await getValueDB(props.property.label, ['state_id', 'municipality_id', 'colony_id']);
                valuesDb.value.state_id = response.state_id;
                valuesDb.value.municipality_id = _.toInteger(response.municipality_id);
                valuesDb.value.colony_id = _.toInteger(response.colony_id);

                if (valuesDb.value.state_id){
                    valState.value = valuesDb.value.state_id;
                }
            }

            $(document).ready(async () => {
                select2.value.state = await convertToSelect2("state_id", optionsState, valState.value, "Seleccionar Estado");
                select2.value.municipio = await convertToSelect2("municipality_id", optionsMunicipio, valMunicipio.value, "Seleccionar Municipio");
                select2.value.colony = await convertToSelect2("colony_id", optionsColony, valColony.value, "Seleccionar Colonia");
            });
        });

        const changeMunicipio = async (state_id) => {
            optionsMunicipio.value = state_id ? await getOptions({
                'model': 'App\\Models\\Municipality',
                'id': 'id',
                'text': 'name',
                'filter': [{
                    'field_relation': 'state',
                    'field': 'id',
                    'value': state_id || null
                }]
            }) : await getOptions({
                'model': 'App\\Models\\Municipality',
                'id': 'id',
                'text': 'name'
            });

            if (_.size(select2.value.municipio)) {
                select2.value.municipio.destroy();
                select2.value.municipio = await convertToSelect2("municipality_id", optionsMunicipio, valuesDb.value.municipality_id, "Seleccionar Municipio");
                valuesDb.value.municipality_id = '';
            }
        }

        const changeColony = async () => {
            optionsColony.value = valMunicipio.value ? await getOptions({
                'model': 'App\\Models\\Colony',
                'id': 'id',
                'text': 'name',
                'filter': [{
                    'field_relation': 'municipio',
                    'field': 'id',
                    'value': valMunicipio.value || null
                }]
            }) : await getOptions({
                'model': 'App\\Models\\Colony',
                'id': 'id',
                'text': 'name'
            });

            if (_.size(select2.value.colony)) {
                select2.value.colony.destroy();
                select2.value.colony = await convertToSelect2("colony_id", optionsColony, valuesDb.value.colony_id, "Seleccionar Colonia");
                valuesDb.value.colony_id = '';
            }
        }

        return {valState, valMunicipio, valColony, optsState, optsMunicipio, optsColony};
    }
}
</script>

<style scoped>

</style>
