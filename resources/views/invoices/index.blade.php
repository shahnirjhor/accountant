@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a href="{{ route('invoice.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New Invoice') }}</a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Invoice List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Invoices') }} </h3>
                <div class="card-tools">
                    <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                </div>
            </div>
            <div class="card-body">
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>{{ __('Number') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Invoice Date') }}</th>
                            <th>{{ __('Due Date')}}</th>
                            <th>{{ __('Status') }}</th>
                            <th data-orderable="false">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ $invoice->amount }}</td>
                            <td>{{ date($company->date_format, strtotime($invoice->invoiced_at)) }}</td>
                            <td>{{ date($company->date_format, strtotime($invoice->due_at)) }}</td>
                            <td>{{ $invoice->invoice_status_code }}</td>
                            <td>
                                <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="View"><i class="fa fa-eye ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="{{ route('invoice.edit', $invoice) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="#" data-href="{{ route('invoice.destroy', $invoice) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.invoice.index.js')
@endsection
