@extends('meganet.layout.master')
@section('title') @lang('translation.Dashboard') @endsection

@section('content')
    <Breadcrumb
        :list=[{title:"Pagina"},{title:"Cliente",active:"active"}]
    ></Breadcrumb>
    <Datatable
        module="cliente"
        model="Client"
        @can($group.'_add_' . $module)
        add="Agregar Cliente"
        @endcan
        list="Listado de Clientes"
    ></Datatable>
    @if(session()->has('message'))
        <Message
            message="{{ session()->get('message') }}"
            module="Cliente"
        ></Message>
    @endif
@endsection
