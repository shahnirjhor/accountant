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
      <div class="invoice p-3 mb-3">
        <div class="row">
          <div class="col-12">
            <h4>
              <i class="fas fa-globe"></i> {{ $company->company_name ?? '' }}
              <small class="float-right">Date: 2/10/2014</small>
            </h4>
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            From
            <address>
              <strong>Admin, Inc.</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (804) 123-5432<br>
              Email: info@almasaeedstudio.com
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong>John Doe</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (555) 539-1037<br>
              Email: john.doe@example.com
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b>Invoice #007612</b><br>
            <br>
            <b>Order ID:</b> 4F3S8J<br>
            <b>Payment Due:</b> 2/22/2014<br>
            <b>Account:</b> 968-34567
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
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
              <tr>
                <td>1</td>
                <td>Call of Duty</td>
                <td>455-981-221</td>
                <td>$64.50</td>
              </tr>
              <tr>
                <td>1</td>
                <td>Need for Speed IV</td>
                <td>247-925-726</td>
                <td>$50.00</td>
              </tr>
              <tr>
                <td>1</td>
                <td>Monsters DVD</td>
                <td>735-845-642</td>
                <td>$10.70</td>
              </tr>
              <tr>
                <td>1</td>
                <td>Grown Ups Blue Ray</td>
                <td>422-568-642</td>
                <td style="float: right">$25.99</td>
              </tr>
              </tbody>
            </table>
          </div>
          <!-- /.col -->
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
                <tr>
                  <th style="width:75%">Subtotal:</th>
                  <td style="float: right">$250.30</td>
                </tr>
                <tr>
                  <th>Tax (9.3%)</th>
                  <td style="float: right">$10.34</td>
                </tr>
                <tr>
                  <th>Shipping:</th>
                  <td style="float: right">$5.80</td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td style="float: right">$265.24</td>
                </tr>
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
