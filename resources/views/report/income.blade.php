@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Income Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Income Summary') }}</h3>



                <div class="card-tools">
                    <form>
                        <div class="form-row">
                            <div class="col-1">
                                <button class="btn btn-default" data-toggle="collapse" href="#filter"> @lang('2022')</button>
                            </div>
                            <div class="col-3">
                            <input type="text" class="form-control" placeholder="City">
                            </div>
                            <div class="col-3">
                            <input type="text" class="form-control" placeholder="State">
                            </div>
                            <div class="col-3">
                            <input type="text" class="form-control" placeholder="Zip">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table table-responsive">
                <div id="filter" class="collapse @if(request()->isFilterActive) show @endif">
                    <div class="card-body border">
                        <form action="" method="get" role="form" autocomplete="off">
                            <input type="hidden" name="isFilterActive" value="true">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Invoice Number')</label>
                                        <input type="text" name="invoice_number" class="form-control" value="{{ request()->invoice_number }}" placeholder="@lang('Invoice Number')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Invoice Date')</label>
                                        <input type="text" name="invoiced_at" class="form-control flatpickr" value="{{ request()->invoiced_at }}" placeholder="@lang('Invoice Date')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Amount')</label>
                                        <input type="text" name="amount" class="form-control" value="{{ request()->amount }}" placeholder="@lang('Amount')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('invoice.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="charts">
                            <div class="charts-chart">
                                <div>
                                    <canvas id="PQFfRtWHpU" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <table class="table table-striped compact table-width table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th>@lang('Category')</th>
                            @foreach($dates as $date)
                                <th class="text-right">{{ $date }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if ($incomes)
                            @foreach($incomes as $category_id =>  $category)
                                <tr>
                                    <td>{{ $categories[$category_id] }}</td>
                                    @foreach($category as $item)
                                        <td class="text-right">@money($item['amount'], $company->default_currency, true)</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="13">
                                    <h5 class="text-center">@lang('No Records')</h5>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('Totals')</th>
                            @foreach($totals as $total)
                                <th class="text-right">@money($total['amount'], $total['currency_code'], true)</th>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var ctx = document.getElementById("PQFfRtWHpU")
    var data = {
        labels: {!! $myMonth !!},
        datasets: [
            {
                fill: true,
                label: "Income",
                lineTension: 0.3, borderColor: "#00c0ef",
                backgroundColor: "#00c0ef",
                data: {!! $myIncomesGraph !!},
            },
        ]
    };

    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true,
                position: 'top'
            },
        }
    });
</script>
@endsection
