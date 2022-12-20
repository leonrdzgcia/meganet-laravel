<template>
  <div>
    <!--     Section of target   -->
    <div class="row">
      <tarjet-ticket v-for="val in stats"
        :icon="val.icon"
        :label="val.estado"
        :value="val.total"
        :link="val.link"
        :porcent="val.porcent"
        :labelPorcent="val.time_human"
      ></tarjet-ticket>
    </div>
    <!--     End Section of target   -->

    <!--    Section of Recient activities -->
    <!-- <div class="row">
      <recent-activity lista=""></recent-activity>
    </div> -->

    <!--     End Section of Recient activities  -->

    <div class="row">
      <!--     Section of  Assined ticket -->
      <assigned-ticket ></assigned-ticket>
      <!--     End Section of Assined ticket  -->

      <!--     Section of  Assined ticket to admin-->
      <list-assigned-ticket
       
      ></list-assigned-ticket>
    </div>
    <!--     End Section of Assined ticket  -->
  </div>
</template>

<script>
import {onMounted, ref} from 'vue';
import TarjetTicket from "./component/TarjetTicket";
import RecentActivity from "./component/RecentActivity";
import AssignedTicket from "./component/AssignedTicket";
import ListAssignedTicket from "./component/ListAssignedTicket";
import { requestStatisticsForTarjetsByStatus } from "./helper/request";
export default {
  name: "DashboardTicket",
  components: {
    TarjetTicket,
    RecentActivity,
    AssignedTicket,
    ListAssignedTicket,
  },
  props: {

  },
  setup() {
    const stats = ref({});

       onMounted(() => {
          getStatisticsForTarjetsByStatus();
        });

    const getStatisticsForTarjetsByStatus = async () => {
      stats.value = await requestStatisticsForTarjetsByStatus();
      };

    return {
      stats
    };
  },
};
</script>

<style scoped>
</style>
