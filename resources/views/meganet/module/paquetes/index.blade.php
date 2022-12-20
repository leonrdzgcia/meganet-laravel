@extends('meganet.layout.master')
@section('title') @lang('translation.Dashboard') @endsection

@section('content')
    <Breadcrumb
        :list=[{title:"Planes"},{title:"Paquete",active:"active"}]
    ></Breadcrumb>
    <Datatable
        module="paquetes"
        model="Bundle"
        @can($group.'_add_'.$module)
        add="Agregar Plan Paquetes"
        @endcan
        list="Listado de Planes de Paquetes"
    ></Datatable>
    @if(session()->has('message'))
        <Message
            message="{{ session()->get('message') }}"
            module="Paquete"
        ></Message>
    @endif
@endsection
