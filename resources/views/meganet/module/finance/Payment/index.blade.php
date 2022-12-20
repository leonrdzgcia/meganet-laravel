@extends('meganet.layout.master')
@section('title') @lang('translation.Dashboard') @endsection

@section('content')
    <Breadcrumb
        :list=[{title:"Finanzas"},{title:"Pagos",active:"active"}]
    ></Breadcrumb>
    <Datatable
        module="finanzas/pagos"
        model="FinancePayment"
        list="Listado Pagos"
    ></Datatable>
    @if(session()->has('message'))
        <Message
            message="{{ session()->get('message') }}"
            module="FinancePayment"
        ></Message>
    @endif
@endsection


