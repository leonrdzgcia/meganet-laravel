<template>
    <div>
        <tabs @changeTab="changeTab">
            <tab title="VER RED" tab="ver" :active="true">
                <Datatable
                    :id="id"
                    module="red/ipv4/ip"
                    model="NetworkIp"
                    list="Listado de IP"
                ></Datatable>
            </tab>
            <tab title="VISIÓN GENERAL DE LA RED" tab="Vision">
                <NetworkOverview
                    :id="id"
                ></NetworkOverview>
            </tab>
        </tabs>
    </div>
</template>

<script>
import Tabs from "../../base/shared/tabs/Tabs";
import Tab from "../../base/shared/tabs/Tab";
import Datatable from "../../base/shared/Datatable";
import NetworkOverview from "./NetworkOverview";
import {reactive, ref} from "vue";

export default {
    name: "NetworkVer",
    components: {Datatable, Tabs, Tab, NetworkOverview},
    props: {
        id: String
    },
    setup(props) {
        const tabs = reactive({
            Mikrotik: false,
            Vision: false
        });

        const changeTab = ({tab}) => {
            tabs[tab] = true;
        };

        return {
            changeTab,
            tabs,
        };
    },
};
</script>

<style scoped>

</style>
