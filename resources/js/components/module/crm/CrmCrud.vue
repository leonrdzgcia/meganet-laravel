<template>
    <div>
        <tabs @changeTab="changeTab">
            <tab title="Información"  tab="Informacion" :active="true" v-if="showTab.Informacion">
                <InformationCrmCrud :action="`update/${id}`" :id="id"></InformationCrmCrud>
            </tab>
            <tab title="Documentos" tab="Documentos" v-if="showTab.Documentos">
                <DocumentCrmCrud
                    :id="id"
                    v-if="tabs.Documentos"
                    :editModal="editModal"
                ></DocumentCrmCrud>
            </tab>
        </tabs>
    </div>
</template>

<script>
import InformationCrmCrud from "./InformationCrmCrud";
import DocumentCrmCrud from "./document/DocumentCrmCrud";
import Tabs from "../../base/shared/tabs/Tabs";
import Tab from "../../base/shared/tabs/Tab";
import {onMounted, ref, reactive} from "vue";
import {editModal, showEditModal} from "../../../hook/modalHook";

export default {
    name: "CrmCrud",
    props: {
        id: {
            type: String,
            default: null,
        },
        show: String
    },
    components: {
        Tabs,
        Tab,
        InformationCrmCrud,
        DocumentCrmCrud,
    },
    setup(props) {
        const showTab = ref(_.mapKeys(JSON.parse(props.show) , v => v ));

        const tabs = reactive({
            Informacion: true,
            Documentos: false
        });

        const changeTab = ({ tab }) => {
            tabs[tab] = true;
        };

        onMounted(() => {
            $(document).on("click", ".uil-pen-modal", function () {
                let idItem = $(this).parent().attr("id-item");
                let modal = $(this).parent().attr("toggle-modal");
                showEditModal(idItem, modal);
            });
        });

        return {
            changeTab,
            tabs,
            editModal,
            showTab
        };
    },
};
</script>

<style scoped></style>
