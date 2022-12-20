@extends('meganet.layout.master')

@section('content')
    <google-map>

    </google-map>
@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MIX_VUE_APP_GOOGLEMAPS_KEY') }}&libraries=drawing">
@endsection
