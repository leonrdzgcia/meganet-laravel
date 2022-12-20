<template>
    <InputText
        v-if="json.type == 'input-string' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @click="clearError(json.field)"
        @update-field="updateThisField"
    />

    <InputInformation
        v-if="json.type == 'input-string-information' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @click="clearError(json.field)"
        @update-field="updateThisField"
    />

    <InputHidden
        v-if="json.type == 'input-hidden' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @click="clearError(json.field)"
        @update-field="updateThisField"
    />

    <Text
        v-if="json.type == 'text' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @click="clearError(json.field)"
        @update-field="updateThisField"
    />

    <InputTextArea
        v-if="json.type == 'input-text-area' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputFile
        v-if="json.type == 'input-file' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputMultipleFile
        v-if="json.type == 'input-multiple-file' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputPassword
        v-if="json.type == 'input-password' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
        @click="clearError(json.field)"
    />

    <InputPasswordInModal
        v-if="json.type == 'input-password-in-modal' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputGroupGenerateUser
        v-if="json.type == 'input-group-generate-user' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
        @click="clearError(json.field)"
    />

    <InputGroupText
        v-if="json.type == 'input-group-text' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputGroupNumber
        v-if="json.type == 'input-group-number' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputGroupTextInit
        v-if="json.type == 'input-group-text-init' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputGroupMultiple
        v-if="json.type == 'input-group-multiple' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputNumber
        v-if="json.type == 'input-number' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputCheckbox
        v-if="json.type == 'input-checkbox' && json.include"
        :property="json"
        :errors="errors"
        @change="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputCheckboxLeftOrder
        v-if="json.type == 'input-checkbox-left-order' && json.include"
        :property="json"
        :errors="errors"
        @change="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputCheckboxWithInputs
        v-if="json.type == 'input-checkbox-with-inputs' && json.include"
        :property="json"
        :errors="errors"
        @change="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <SelectComponetWithGroupInputs
        v-if="json.type == 'select-component-with-group-inputs' && json.include"
        :property="json"
        :errors="errors"
        :idModel="idModel"
        @change="clearError(json.field)"
        @clear-depend-errors="clearDependErrors"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <DateTimeLocal
        v-if="json.type == 'date-time-local' && json.include"
        :property="json"
        :errors="errors"
        @change="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <SelectComponent
        v-if="json.type == 'select-component' && json.include"
        :property="json"
        :errors="errors"
        @change="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <Select2Component
        v-if="json.type == 'select-2-component' && json.include"
        :property="json"
        :errors="errors"
        :idModel="idModel"
        @change="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <Select2EstadoMunicipioColoniaComponent
        v-if="json.type == 'select-2-estado-municipio-colonia-component'"
        :property="json"
        :errors="errors"
        @change="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <SelectComponentWithCheckbox
        v-if="
            (json.type == 'select-component-with-checkbox' ||
            json.type == 'select-component-with-checkbox-without-id') &&
            json.include
        "
        :id="
            json.type == 'select-component-with-checkbox-without-id' ? id : null
        "
        :property="json"
        :errors="errors"
        @click="clearError(json.field)"
        v-model="modelValue"
        @update-field="updateThisField"
    />

    <InputCheckboxAfterWithouValidationError
        v-if="json.type == 'input-checkbox-after-withou-validation-error' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @change="clearError(json.field)"
        @update-field="updateThisField"
    />

    <SelectSingleAddItems
        v-if="json.type == 'select-single-add-items' && json.include"
        :property="json"
        :errors="errors"
        v-model="modelValue"
        @update-field="updateThisField"
    />
</template>

<script type="application/javascript">
import InputText from "../shared/InputText";
import InputHidden from "../shared/InputHidden";
import Text from "../shared/Text";
import InputTextArea from "../shared/InputTextArea";
import InputPassword from "../shared/InputPassword";
import InputNumber from "../shared/InputNumber";
import InputGroupText from "../shared/InputGroupText";
import InputGroupMultiple from "../shared/InputGroupMultiple";
import InputGroupTextInit from "../shared/InputGroupTextInit";
import InputGroupNumber from "../shared/InputGroupNumber";
import InputCheckbox from "../shared/InputCheckbox";
import InputCheckboxLeftOrder from "../shared/InputCheckboxLeftOrder";
import InputCheckboxWithInputs from "../shared/InputCheckboxWithInputs";
import SelectComponetWithGroupInputs from "../shared/SelectComponetWithGroupInputs";

import InputCheckboxAfterWithouValidationError from "../shared/InputCheckboxAfterWithouValidationError";
import SelectComponent from "../shared/SelectComponent";
import Select2Component from "../shared/Select2Component";
import SelectComponentWithCheckbox from "../shared/SelectComponentWithCheckbox";
import InputPasswordInModal from "../shared/InputPasswordInModal";
import InputGroupGenerateUser from "../shared/InputGroupGenerateUser";
import SelectSingleAddItems from "../components/funcionales/SelectSingleAddItems";
import DateTimeLocal from "../shared/DateTimeLocal";
import InputFile from "../shared/InputFile";
import InputMultipleFile from "../shared/InputMultipleFile";
import InputInformation from "../shared/InputInformation";
import Select2EstadoMunicipioColoniaComponent from "../shared/Select2EstadoMunicipioColoniaComponent";

export default {
    name: "ComponentFormDefault",
    props: {
        id: {
            type: String,
            default: null,
        },
        json: Object,
        errors: {
            type: Object,
            default: {},
        },
        idModel: {
            type: String,
            default: null,
        },
        modelValue: String | Array,
    },
    emits: ["update-field", "clear-error", "clear-depend-error"],
    components: {
        Select2EstadoMunicipioColoniaComponent,
        InputInformation,
        InputFile,
        InputMultipleFile,
        InputText,
        InputHidden,
        Text,
        InputTextArea,
        InputPassword,
        InputNumber,
        InputGroupText,
        InputGroupMultiple,
        InputGroupTextInit,
        InputGroupNumber,
        InputCheckbox,
        InputCheckboxLeftOrder,
        InputCheckboxWithInputs,
        InputCheckboxAfterWithouValidationError,
        SelectComponent,
        Select2Component,
        SelectComponentWithCheckbox,
        InputPasswordInModal,
        InputGroupGenerateUser,
        SelectSingleAddItems,
        DateTimeLocal,
        SelectComponetWithGroupInputs,
    },
    setup(props, { emit }) {
        const updateThisField = (value) => {
            emit("update-field", value);
        };

        const clearError = (field) => {
            emit("clear-error", { field });
        };

        const clearDependErrors = ({ field }) => {
            emit("clear-error", { field });
        };

        return {
            updateThisField,
            clearError,
            clearDependErrors,
        };
    },
};
</script>
