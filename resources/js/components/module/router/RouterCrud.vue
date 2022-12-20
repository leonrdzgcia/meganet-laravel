<template>
    <div>
        <tabs @changeTab="changeTab">
            <tab title="Información" tab="Informacion" :active="true">
                <InformationRouterCrud :action="`update/${id}`" :id="id" />
            </tab>
            <tab title="Mikrotik" tab="Mikrotik">
                <MikrotikRouterCrud
                    :action="`update/${id}`"
                    :id="id"
                    v-if="tabs.Mikrotik"
                />
            </tab>
              <tab title="Configuración adicional Mikrotik" tab="MikrotikConfig">
            <MikrotikConfigCrud
                    :action="`update/${id}`"
                    :id="id"
                    v-if="tabs.MikrotikConfig"
                />
            </tab>
        </tabs>
    </div>
</template>

<script>
import Tabs from "../../base/shared/tabs/Tabs";
import Tab from "../../base/shared/tabs/Tab";
import MikrotikRouterCrud from "./MikrotikRouterCrud";
import InformationRouterCrud from "./InformationRouterCrud";
import MikrotikConfigCrud from "./MikrotikConfigCrud";
import { reactive, ref } from "vue";

export default {
    name: "RouterCrud",
    props: {
        action: String,
        id: {
            type: String,
            default: null,
        },
    },
    components: {
        Tabs,
        Tab,
        InformationRouterCrud,
        MikrotikRouterCrud,
        MikrotikConfigCrud
    },

    setup() {
        const tabs = reactive({
            Mikrotik: false,
        });

        const changeTab = ({ tab }) => {
            tabs[tab] = true;
        };

        return {
            tabs,
            changeTab,
        };
    },
};
</script>

<style scoped></style>
