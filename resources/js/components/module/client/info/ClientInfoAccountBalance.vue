<template>
    <div class="col-lg-7 col-md-7 col-sm-7 align-self-center" v-if="data.name">
        <b class="customer-name-wrapper me-1">{{ data.name }}</b> |
        <span class="customer-billing-balance-title">
            Account balance:
        </span><b class="customer-balance">$ {{ data.balance }}</b>
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import {getClientWithBalance} from "../helpers/helper";

export default {
    name: "ClientInfoAccountBalance",
    props: {
        client_id: String
    },
    setup(props){
        const data = ref({
            balance: null,
            name: null
        });
        onMounted(async () => {
            data.value = await getClientWithBalance(props.client_id);
        })
        return {data};
    }
}
</script>

<style scoped>

</style>
