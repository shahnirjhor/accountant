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
        <!---Income-->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">@lang('Total Incomes')</span>
                    <span class="info-box-number">@money($total_incomes['total'], $company->default_currency, true)</span>
                    <div class="progress-group" title="@lang('Open Invoices') {{ $total_incomes['open_invoice'] }}<br>@lang('Overdue Invoices'): {{ $total_incomes['overdue_invoice'] }}" data-toggle="tooltip" data-html="true">
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-aqua" style="width: {{ $total_incomes['progress'] }}%"></div>
                        </div>
                        <span class="progress-text">@lang('Receivables')</span>
                        <span class="progress-number">{{ $total_incomes['open_invoice'] }} / {{ $total_incomes['overdue_invoice'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!---Expense-->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">@lang('Total Expenses')</span>
                    <span class="info-box-number">@money($total_expenses['total'], $company->default_currency, true)</span>

                    <div class="progress-group" title="@lang('Open Bills') {{ $total_expenses['open_bill'] }}<br>@lang('Overdue Bills') {{ $total_expenses['overdue_bill'] }}" data-toggle="tooltip" data-html="true">
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-red" style="width: {{ $total_expenses['progress'] }}%"></div>
                        </div>
                        <span class="progress-text">@lang('Payables')</span>
                        <span class="progress-number">{{ $total_expenses['open_bill'] }} / {{ $total_expenses['overdue_bill'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!---Profit-->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-heart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">@lang('Total Profit')</span>
                    <span class="info-box-number">@money($total_profit['total'], $company->default_currency, true)</span>

                    <div class="progress-group" title="@lang('Open Profit') {{ $total_profit['open'] }}<br>@lang('Overdue Profit') {{ $total_profit['overdue'] }}" data-toggle="tooltip" data-html="true">
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-green" style="width: {{ $total_profit['progress'] }}%"></div>
                        </div>
                        <span class="progress-text">@lang('Upcoming')</span>
                        <span class="progress-number">{{ $total_profit['open'] }} / {{ $total_profit['overdue'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('script.dashboard.view.js')

@endsection
