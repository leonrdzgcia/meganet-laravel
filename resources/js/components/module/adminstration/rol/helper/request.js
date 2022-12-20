import axios from "axios";

export const requestPermissionForRole = async (rol) => {
    let fields = {}
    await axios["post"](`/administracion/permisos/get-permission-for-role/${rol}`, {}).then((response) => {
        fields = response.data;
    });
    return fields;
}
