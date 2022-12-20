import Errors from "./Errors";
import _ from "lodash";

class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data
            ? _.mapValues(data, (v) => (v && v.value) || null)
            : {};
        this.originalJson = data;

        let values = _.cloneDeep(this.originalData);
        for (let field in values) {
            this[field] = values[field];
        }

        this.errors = new Errors();
        this.responseForbidden = null;
    }

    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property] && this[property] != 'null' ? this[property] : null;

            let type = typeof this.originalJson[property] == 'object' ? this.originalJson[property].type : '';
            if (
                [
                    "input-checkbox-with-inputs",
                    "input-checkbox",
                    "input-checkbox-after-withou-validation-error",
                ].includes(type) &&
                !this[property]
            ){
                data[property] = false;
            }
        }

        return data;
    }

    /**
     * Reset the form fields.
     */
    reset() {
        for (let field in this.originalData) {
            this[field] = null;
        }

        this.errors.clear();
    }

    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     */
    post(url) {
        return this.submit("post", url);
    }

    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit("put", url);
    }

    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */
    patch(url) {
        return this.submit("patch", url);
    }

    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */
    delete(url) {
        return this.submit("delete", url);
    }

    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */
    submit(requestType, url, type = "reset") {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                .then((response) => {
                    this.onSuccess(response.data, type);

                    resolve(response.data);
                })
                .catch((error) => {
                    if (error.response.status === 422){
                        this.onFail(error.response.data.errors);

                        reject(error.response.data);
                    }else{
                        if (error.response.status === 403){
                            this.actionForbidden(error.response.data);
                        }
                    }
                });
        });
    }

    uploadFile(url, type = "reset") {
        let data = new FormData();

        for (let property in this.originalData) {
            if (this[property] != null && (
                property != 'files' || property !=  'attachments'
            )) data.append(property, this[property]);

            if (this[property] != null && (property == 'files' || property ==  'attachments')){
                for( var i = 0; i < this[property].length; i++ ){
                    let file = this[property][i];
                    data.append(`${property}[${i}]`, file);
                }
            }
        }

        return new Promise((resolve, reject) => {
            axios["post"](url, data, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
                .then((response) => {
                    this.onSuccess(response.data, type);

                    resolve(response.data);
                })
                .catch((error) => {
                    if (error.response.status === 422){
                        this.onFail(error.response.data.errors);

                        reject(error.response.data);
                    }else{
                        if (error.response.status === 403){
                            this.actionForbidden(error.response.data);
                        }
                    }
                });
        });
    }

    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */
    onSuccess(data, type) {
        if (type == "reset") this.reset();
    }

    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        this.errors.record(errors);
    }

    actionForbidden(data){
        this.responseForbidden = data;
    }

    showResponseForbidden(){
        return this.responseForbidden;
    }
}

export default Form;
