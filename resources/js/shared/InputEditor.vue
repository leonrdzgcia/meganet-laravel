<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ title }}</h5>
                    <div :id="field"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { convertToCkeditor } from "../helpers/Transform";
import { ref, watch, onMounted } from "vue";

export default {
    name: "InputEditor",
    props: {
        field: String,
        title: String,
        modelValue: {
            type: String,
            default: null,
        },
    },
    setup(props, { emit }) {
        const editor = ref();

        watch(editor, () => {
            emit("update-field", { value: editor, field: props.field });
        });

        onMounted(async () => {
            $(document).ready(function () {
              convertToCkeditor(props.field, editor);
            });
        });

        return {};
    },
};
</script>

<style scoped></style>
