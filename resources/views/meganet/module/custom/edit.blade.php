@extends('meganet.layout.master')
@section('title') @lang('translation.Dashboard') @endsection

@section('content')
    <custom-crud
        action="update/{{$id}}"
        id="{{ $id }}"
    ></custom-crud>
@endsection
