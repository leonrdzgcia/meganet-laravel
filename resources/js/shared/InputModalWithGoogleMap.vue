<template>
    <div class="form-group row">
        <label
            for="inputGoogleMap"
            class="
                col-sm-12 col-md-3 col-form-label
                text-sm-center text-md-end
            "
        ></label>
        <div class="input-group col-sm-12 col-md-8">
            <input
                id="inputGoogleMap"
                type="text"
                :class="{ 'form-control': true }"
                style="background-color: gainsboro"
                disabled
            />
            <div class="input-group-append">
                <span
                    class="input-group-text bg-primary text-white"
                    :id="`${field}1`"
                    ><i
                        class="uil-bookmark cursor-pointer"
                        @click="showModal"
                    ></i
                ></span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGoogleMap">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">Elige tu ubicacion</div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-xl-8">
                            <GoogleMap
                                v-model="val"
                                :key="val"
                            />
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>Coordenadas</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="24.2321511, -102.8257218"
                                @keypress.enter.prevent="setCoordenate($event.target.value)"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref } from "vue";
import Modal from "../helpers/modal";
import GoogleMap from "../components/base/googlemap/GoogleMap";

export default {
    name: "InputModalWithGoogleMap",
    props: {
        position: String,
    },
    components: {
        GoogleMap,
    },
    setup(props) {
        const modal = ref();
        const val = ref(props.position)

        onMounted(() => {
            modal.value = new Modal("modalGoogleMap");
        });

        const showModal = () => {
            modal.value.show();
        };

        const setCoordenate = (value) => {
            val.value = value
        }

        return {
            showModal,
            setCoordenate,
            val
        };
    },
};
</script>

<style scoped></style>
