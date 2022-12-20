export const selectTransform = (val) => {
    let opts = [];
    if(_.size(val) > 0){
        opts = _.map(val,(v,k) => {
            return {value: k, text: v}
        });
    }
    return opts;
}

export const getOptions = async (val, idModel = null) => {
    let opts = [];
    if (val)
        await axios.post('/get-options-select',{...val, idModel: idModel})
            .then(res => opts = selectTransform(res.data))
    return opts;
}

export const getOptionsWithoutId = async (val,id) => {
    let opts = [];
    await axios.post(`/get-options-select/${id}`,{...val})
        .then(res => opts = selectTransform(res.data))
    return opts;
}

export const convertToBoostrapSelect = async (id, selected, allOptions) => {
    await $(`#${id}`).multiselect({
        includeSelectAllOption: true,
        onChange: function(option, checked, select) {
            if (checked){
                let item = selected.value.length > 0 ? selected.value : [];
                item.push($(option).val())
                selected.value = item;
            } else {
                selected.value = _.filter(selected.value, (v) => { return v = $(option).val() }).length ?
                    _.filter(selected.value, (v) => { return v != $(option).val() }) : selected.value
            }
        },
        onSelectAll: function() {
            selected.value = _.map(allOptions, (opt) => opt.value);
        },
        onDeselectAll: function() {
            selected.value = [];
        }
    });

    // Adaptando a bootstrap5
    $(`#${id}`).parent().find('div.btn-group > button').removeAttr('data-toggle')
    $(`#${id}`).parent().find('div.btn-group > button').attr('data-bs-toggle', 'dropdown')
}

export const convertToSelect2 = async (id, allOptions, val, placeholder) => {
    let options = _.map(allOptions.value, opt => {
        if (opt.value == val) return { value: opt.value, label: opt.text, selected: true}
        return { value: opt.value, label: opt.text}
    });

    let choice = new Choices(`#${id}`, {
        shouldSort: false,
        choices: [
            { value: 'null', label: placeholder },
        ]
    });
    choice.setChoices(
        options,
        'value',
        'label',
        false
    );
    return choice;
}

// TODO ver para hacer el date range picker
export const convertToCkeditor = async (id, editor) => {
    return await ClassicEditor
        .create( document.querySelector( `#${id}` ) )
        .then( e => {
            e.model.document.on( 'change:data', (v) => {
                editor.value = e.getData();
            });
        } )
        .catch( error => {
            console.error( error );
        } );
}

export const convertToDateRangePicker = async (id) => {
    return await $(`#${id}-range`).daterangepicker();
}

export const arrayOfObjectToArrayOfArray = (array) => {
    return array.map(function(obj) {
        return Object.keys(obj).sort().map(function(key) {
            return obj[key];
        });
    });
}

export const getLocation = (geodata) => {
    if(geodata) {
        let location = _.split(geodata,',');
        if (location.length == 2){
            return {lat:  _.toNumber(location[0]), lng: _.toNumber(location[1])};
        }
    }else{
        return {lat:  23.1188897, lng:  -82.3925687}
    }
};
