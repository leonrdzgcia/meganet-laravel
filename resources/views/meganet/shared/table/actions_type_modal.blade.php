<td>
    @if(isset($documentslip))
    <a
        href="{{ $documentslip }}"
        data-toggle="tooltip"
        data-placement="top"
        title="Descargar Documento"
        target="_black"
    ><i class="fas fa-file-pdf"></i></a>
@endif
    @if(isset($document))
        <a
            href="{{ $document }}"
            data-toggle="tooltip"
            data-placement="top"
            title="Descargar Documento"
            download
        ><i class="fas fa-download"></i></a>
    @endif
    @if(\Illuminate\Support\Facades\Auth::user()->can($group.'_edit_'.$submodule) || \Illuminate\Support\Facades\Auth::user()->isAdmin())
        <a
            class="mr-2"
            href="javascript:void(0);"
            toggle-modal="{{ $modal }}"
            id-item="{{ $id }}"
            data-toggle="tooltip"
            data-placement="top"
            title="Editar"
        ><i class="far fa-edit uil-pen-modal"></i></a>
    @endif
    @if(\Illuminate\Support\Facades\Auth::user()->can($group.'_delete_'.$submodule) || \Illuminate\Support\Facades\Auth::user()->isAdmin())
        <a
            class=""
            href="javascript:void(0);"
            id-item="{{ $id }}"
            data-toggle="tooltip"
            data-placement="top"
            title="Borrar"
        ><i class="fas fa-trash"></i></a>
    @endif
</td>
