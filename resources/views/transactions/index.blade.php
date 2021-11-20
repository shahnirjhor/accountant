@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Transaction List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Transactions') </h3>
                <div class="card-tools">
                    <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                </div>
            </div>
            <div class="card-body">
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>@lang('Date')</th>
                            <th>@lang('Account Name ')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ date($company->date_format, strtotime($transaction['paid_at'])) }}</td>
                                <td>{{ $transaction['account_name'] }}</td>
                                <td>{{ $transaction['type'] }}</td>
                                <td>{{ $transaction['category_name'] }}</td>
                                <td>@money($transaction['amount'], $transaction['currency_code'], true)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $transactions->links() }} --}}
            </div>
        </div>
    </div>
</div>
<script>    
    "use strict";
    $(document).ready( function () {
        $('#laravel_datatable').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endsection