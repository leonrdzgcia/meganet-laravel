@extends('meganet.layout.master')
@section('title') @lang('translation.Dashboard') @endsection

@section('content')
    <Breadcrumb
        :list=[{title:"Red"},{title:"Ipv4"},{title:"Ip"},{title:"Listar",active:"active"}]
    ></Breadcrumb>

    <network-ver
        id="{{ $id }}"
    ></network-ver>

    @if(session()->has('message'))
        <Message
            message="{{ session()->get('message') }}"
            module="red/ipv4"
        ></Message>
    @endif
@endsection


