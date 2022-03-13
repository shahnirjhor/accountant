@extends('layouts.layout')

@section('one_page_css')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection
@section('one_page_js')
<script src="{{ asset('plugins/bower_components/chart.js/bundle.js') }}"></script>
<script src="{{ asset('plugins/bower_components/chart.js/utils.js') }}"></script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">{{ __('dashboard.dashboard') }}</h4>
            <p class="text-muted page-title-alt">{{ __('dashboard.welcome to') }} {{ $ApplicationSetting->item_short_name }} {{ __('dashboard.panel') }}</p>
        </div>
    </div>
</div>
@include('script.dashboard.view.js')

@endsection
