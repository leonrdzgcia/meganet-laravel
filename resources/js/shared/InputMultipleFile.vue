<template>
  <div :class="`row m-auto mb-2 ${errors.has(property.field) && 'has-danger'}`">
    <div class="col-sm-12 col-lg-3"></div>
    <div class="col-sm-12 col-lg-8">
      <input
          type="file"
          multiple
          class="custom-file-input"
          :id="`custom_${property.field}`"
          :name="property.field"
          @change="uploadFile"
      />
      <label
          class="custom-file-label"
          :for="`custom_${property.field}`"
      >{{ fileName }}</label>
    </div>
    <div v-if="errors.has(property.field)" class="pristine-error text-help">
      {{ errors.get(property.field) }}
    </div>
  </div>
</template>

<script>
import {ref, watch} from "vue";

export default {
  name: "InputMultipleFile",
  props: {
    errors: {
      type: Object,
      default: {},
    },
    property: Object,
    modelValue: String | File,
  },
  setup(props, {emit}) {
    const val = ref(props.modelValue);
    const fileName = ref("");
    try {
      let file = JSON.parse(props.modelValue);
      fileName.value = file.name;
    } catch (e) {
      fileName.value = props.modelValue;
    }

    const uploadFile = (e) => {
      if (e.target.files.length) {
        fileName.value = _.join(_.map(e.target.files, f => f.name), ', ');
        val.value = e.target.files;
      }
    };

    watch(val, () => {
      if (!val.value) fileName.value = '';
      emit("update-field", {value: val, field: props.property.field});
    });

    return {
      val,
      fileName,
      uploadFile,
    };
  },
};
</script>

<style scoped></style>
