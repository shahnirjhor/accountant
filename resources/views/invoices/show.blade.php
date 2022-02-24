@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('invoice.index') }}">@lang('Invoices List')</a></li>
                    <li class="breadcrumb-item active">@lang('Show Invoice')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="invoice p-3 mb-3 card card-warning card-outline">
            <div class="row">
                <div class="col-12 ">
                    <h4>
                        <i class="fas fa-globe"></i> {{ $company->company_name ?? '' }}
                        <strong><span class="float-right badge badge-warning" style="padding: 10px;">Status: Draft</span></strong>
                    </h4>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong>{{ $salesMan->name}}</strong><br>
                        @if ($company->company_address)
                        {{ strip_tags($company->company_address) }}<br>
                        @endif
                        @if ($company->company_phone)
                        Phone: {{ $company->company_phone }}<br>
                        @endif
                        @if ($company->company_phone)
                        Email: {{ $company->company_email }}
                        @endif
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong>{{ $invoice->customer_name}}</strong><br>
                        @if ($invoice->customer_address)
                        {{ strip_tags($invoice->customer_address) }}<br>
                        @endif
                        @if ($invoice->customer_phone)
                        Phone: {{ $invoice->customer_phone }}<br>
                        @endif
                        @if ($invoice->customer_email)
                        Email: {{ $invoice->customer_email }}
                        @endif
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Invoice #{{$invoice->invoice_number}}</b><br>
                    <br>
                    <b>Order Number:</b> {{$invoice->order_number}}<br>
                    <b>Invoice Date:</b> {{ date($company->date_format, strtotime($invoice->invoiced_at)) }}<br>
                    <b>Payment Due:</b> {{ date($company->date_format, strtotime($invoice->due_at)) }}
                </div>
            </div>
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>@money($item->price, $invoice->currency_code, true)</td>
                                <td>@money($item->total, $invoice->currency_code, true)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        <!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-6">
          </div>
          <!-- /.col -->
          <div class="col-6">
            <div class="table-responsive">
              <table class="table">
                @foreach ($invoice->totals as $total)
                    @php
                        $totalName = explode(".",$total->name);
                        $countNameArray = count($totalName);
                        if($countNameArray == '1') {
                            $name = $totalName[0];
                        } else {
                            $explodeWithunder = explode("_",$totalName[1]);
                            $name = ucwords(implode(" ",$explodeWithunder));
                        }
                    @endphp
                    @if ($total->code != 'total')
                    <tr>
                        <th style="width:50%">{{ $name }}:</th>
                        <td>@money($total->amount, $invoice->currency_code, true)</td>
                    </tr>
                    @else
                        @if ($invoice->paid)
                            <tr>
                                <th style="width:50%">{{ $name }}:</th>
                                <td>@money($invoice->paid, $invoice->currency_code, true)</td>
                            </tr>
                        @endif
                        <tr>
                            <th style="width:50%">{{ $name }}:</th>
                            <td>@money($total->amount - $invoice->paid, $invoice->currency_code, true)</td>
                        </tr>
                    @endif
                @endforeach
              </table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
          <div class="col-12">
            <button type="button" class="btn btn-lg btn-outline-danger float-right" style="margin-right: 5px;">
                <i class="fas fa-trash"></i> Delete
            </button>
            <button type="button" class="btn btn-outline-warning btn-lg float-right" style="margin-right: 5px;">
                <i class="fas fa-ban"></i> Cancel
            </button>
            <button type="button" class="btn btn-outline-dark btn-lg float-right" style="margin-right: 5px;">
                <i class="fas fa-pen"></i> Edit
            </button>
            <button type="button" class="btn btn-lg btn-outline-primary float-right" style="margin-right: 5px;">
                <i class="fas fa-download"></i> Generate PDF
            </button>
            <button type="button" class="btn btn-lg btn-outline-info float-right" style="margin-right: 5px;">
                <i class="fas fa-print"></i> Print
            </button>


            <button type="button" class="btn btn-lg btn-success"><i class="far fa-credit-card"></i> Submit
              Payment
            </button>
          </div>
        </div>
      </div>
      <!-- /.invoice -->
    </div><!-- /.col -->
  </div><!-- /.row -->
@endsection
