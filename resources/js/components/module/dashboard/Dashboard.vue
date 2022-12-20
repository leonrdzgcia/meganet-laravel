<template>
    <div class="row">
        <tarjet-ticket
            v-for="val in stats"
            :icon="val.icon"
            :label="val.estado"
            :value="val.total"
            :link="val.link"
            :porcent="val.porcent"
            :labelPorcent="val.time_human"
        ></tarjet-ticket>
    </div>

    <div class="row mb-3">
        <card-text-dashboard></card-text-dashboard>
    </div>

    <div class="row">
        <card-stats tableName="Clientes" :dataStat="dataStatClient"></card-stats>
        <card-stats tableName="Tickets" :dataStat="dataStatTicket"></card-stats>
    </div>
</template>

<script>
import {onMounted, ref} from "vue";
import TarjetTicket from "../tickets/component/TarjetTicket";
import CardTextDashboard from "./CardTextDashboard.vue";
import CardStats from "./CardStats";
import {
    requestHomeStatisticsForTarjetsByStatus,
    requestStatsClientCardInDashBoard,
    requestStatsTicketCardInDashBoard,
} from "./helper/request";

export default {
    name: "Dashboard",
    components: {
        TarjetTicket,
        CardTextDashboard,
        CardStats,
    },
    props: {},
    setup() {
        const stats = ref({});
        const dataStatClient = ref({});
        const dataStatTicket = ref({});

        onMounted(() => {
            getStatisticsForTarjetsByStatus();
            getStatsClientCardInDashBoard();
            getStatsTicketCardInDashBoard();
        });

        const getStatisticsForTarjetsByStatus = async () => {
            stats.value = await requestHomeStatisticsForTarjetsByStatus();
        };

        const getStatsClientCardInDashBoard = async () => {
            dataStatClient.value = await requestStatsClientCardInDashBoard();
        };

        const getStatsTicketCardInDashBoard = async () => {
            dataStatTicket.value = await requestStatsTicketCardInDashBoard();
        };

        return {
            stats,
            dataStatClient,
            dataStatTicket,
        };
    },
};
</script>

<style scoped>
</style>
