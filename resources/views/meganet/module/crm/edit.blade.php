@extends('meganet.layout.master')
@section('title') @lang('translation.Dashboard') @endsection

@section('content')
    @can('crm_edit_crm')
        <crm-crud
            action="update/{{$id}}"
            show="{{ $show }}"
            id="{{ $id }}"
        >
        </crm-crud>
    @endcan
@endsection
