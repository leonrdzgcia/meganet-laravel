import axios from "axios";
import toastr from "toastr";

export const deleteRowDatatable = async (module, id, table) => {
    await axios["post"](`/${module}/destroy/${id}`,{module: module}).then((response) => {
        toastr.success(`Elemento eliminado correctamente`, module)
        table.ajax.reload();
        return true;
    });
    return false;
}
