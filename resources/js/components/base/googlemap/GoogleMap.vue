<template>
    <div id="map"></div>
</template>

<script>
import {initMap} from "./map";
import {onMounted} from "vue";

export default {
    name: "GoogleMap",
    props: {
        modelValue: String,
    },
    setup(props) {
        const ikey = process.env.MIX_VUE_APP_GOOGLEMAPS_KEY;
        const lat = 19.700586990172585;
        const long = -99.07096803188318;
            onMounted(() => {
                $(document).ready(function (){
                    initMap(lat, long)
                })
            })
        return {ikey}
    }
}
</script>

<style>
 #map {
     width: 100%;
     height: 500px;
 }
</style>
