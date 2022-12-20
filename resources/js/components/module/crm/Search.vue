<template>
    <div class="card">
        <div class="card-body" v-if="result">
            <h4 class="card-title">Coincidencias encontradas </h4>

            <table class="table table-striped col-12">
                <thead>
                <tr>
                    <th scope="col">name</th>
                    <th scope="col">Primer Apellido</th>
                    <th scope="col">Segundo Apelido</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Teléfono2</th>
                    <th scope="col">Correo</th>
                    <th scope="col">RFC/CURP</th>
                    <th scope="col">Calle</th>
                    <th scope="col">No Manzana</th>
                    <th scope="col">No Lote</th>
                    <th scope="col">Acción</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="value in val">
                    <td :class="isIqual(value.crm_main_information.name, dataForm['name'])" v-if="value && value.crm_main_information" >{{ value.crm_main_information.name }}</td>
                    <td :class="isIqual(value.crm_main_information.father_last_name, dataForm['father_last_name'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.father_last_name }} </td>
                    <td :class="isIqual(value.crm_main_information.mother_last_name, dataForm['mother_last_name'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.mother_last_name }}</td>
                    <td :class="isIqual(value.crm_main_information.phone, dataForm['phone'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.phone }}</td>
                    <td :class="isIqual(value.crm_main_information.phone2, dataForm['phone2'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.phone2 }}</td>
                    <td :class="isIqual(value.crm_main_information.email, dataForm['email'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.email }}</td>
                    <td :class="isIqual(value.crm_main_information.nif_pasaport, dataForm['nif_pasaport'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.nif_pasaport }}</td>
                    <td :class="isIqual(value.crm_main_information.street, dataForm['street'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.street }} </td>
                    <td :class="isIqual(value.crm_main_information.external_number, dataForm['external_number'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.external_number }}</td>
                    <td :class="isIqual(value.crm_main_information.internal_number, dataForm['internal_number'])" v-if="value && value.crm_main_information">{{ value.crm_main_information.internal_number }}</td>

                    <td :class="isIqual(value.client_main_information.name, dataForm['name'])" v-if="value && value.client_main_information">{{ value.client_main_information.name }}</td>
                    <td :class="isIqual(value.client_main_information.father_last_name, dataForm['father_last_name'])" v-if="value && value.client_main_information">{{ value.client_main_information.father_last_name }} </td>
                    <td :class="isIqual(value.client_main_information.mother_last_name, dataForm['mother_last_name'])" v-if="value && value.client_main_information">{{ value.client_main_information.mother_last_name }}</td>
                    <td :class="isIqual(value.client_main_information.phone, dataForm['phone'])" v-if="value && value.client_main_information">{{ value.client_main_information.phone }}</td>
                    <td :class="isIqual(value.client_main_information.phone2, dataForm['phone2'])" v-if="value && value.client_main_information">{{ value.client_main_information.phone2 }}</td>
                    <td :class="isIqual(value.client_main_information.email, dataForm['email'])" v-if="value && value.client_main_information">{{ value.client_main_information.email }}</td>
                    <td :class="isIqual(value.client_main_information.nif_pasaport, dataForm['nif_pasaport'])" v-if="value && value.client_main_information">{{ value.client_main_information.nif_pasaport }}</td>
                    <td :class="isIqual(value.client_main_information.street, dataForm['street'])" v-if="value && value.client_main_information">{{ value.client_main_information.street }} </td>
                    <td :class="isIqual(value.client_main_information.external_number, dataForm['external_number'])" v-if="value && value.client_main_information">{{ value.client_main_information.external_number }}</td>
                    <td :class="isIqual(value.client_main_information.internal_number, dataForm['internal_number'])" v-if="value && value.client_main_information">{{ value.client_main_information.internal_number }}</td>

                    <td v-if="value && value.crm_main_information">
                        <a class="btn btn-primary me-2" :href="`/crm/editar/${value.crm_main_information.crm_id}`">
                        Seleccionar
                        </a>
                    </td>
                    <td v-if="value && value.client_main_information">
                        <a class="btn btn-primary me-2" :href="`/cliente/editar/${value.client_main_information.client_id}`">
                            Seleccionar
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script>

export default {
    name: "Search",
    props: {
        val: Object,
        result: Boolean,
        dataForm:Object,
    },
    setup(props) {
        const isIqual = (request , form) => {
            if (request == form) {
                return 'text-danger';
            }
            return '';
        }
        return{
          isIqual
        };
    },
}
</script>

<style scoped>

</style>
