class DatatableHelper {
    constructor(objt) {
        this.table = objt
    }

    reload(){
        this.table.ajax.reload();
    }
}

export default DatatableHelper;
