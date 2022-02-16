@extends('layouts.layout')
@section('one_page_js')
    <!-- Include the Quill library -->
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('plugins/sweetalert2/swal.js') }}"></script>

@endsection

@section('one_page_css')
    <!-- Include quill -->
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/select2/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
<style>
    #t1 th {
        color: #ffffff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('invoice.index') }}">@lang('Invoices List')</a></li>
                    <li class="breadcrumb-item active">@lang('Add Invoice')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Add Invoice')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('invoice.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id">@lang('Customer') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="customer_id" id="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $key => $value)
                                            <option value="{{ $key }}" {{ old('customer_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency_code">@lang('Currency') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="currency_code" id="currency_code" required>
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $key => $value)
                                            <option value="{{ $key }}" {{ old('currency_code') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoiced_at">@lang('Invoice Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="invoiced_at" id="invoiced_at" class="form-control today-flatpickr" value="{{ old('invoiced_at') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_at">@lang('Due Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="due_at" id="due_at" class="form-control flatpickr" value="{{ old('due_at') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">@lang('Invoice Number') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file-signature"></i>
                                    </div>
                                    <input type="text" name="invoice_number" value="{{ old('invoice_number', $number) }}" id="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" required>
                                    @error('invoice_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">@lang('Order Number') </label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <input type="text" name="order_number" value="{{ old('order_number') }}" id="order_number" class="form-control @error('order_number') is-invalid @enderror">
                                    @error('order_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                          <label for="js-example-data-ajax">{{ __('Add Item') }} </label>
                            <div class="form-group input-group mb-3">
                                <div class="barcode">
                                  <div class="row">
                                    <div class="col-bar-icon d-none d-xl-block">
                                        <i class="fa fa-barcode fa-4x" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-sm-11 my-auto col-bar-box">
                                        <select class="js-example-data-ajax select2-container" id="js-example-data-ajax" name="combo_id[]"  multiple="multiple">
                                            <option value="AL">...</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="table-combo">{{ __('product.combo products') }} <b class="ambitious-crimson">*</b></label>
                            <table class="table" id="table-combo">
                              <thead>
                                <tr class="bg-info">
                                    <th scope="col" style="white-space: nowrap;">@lang('Account Name')</th>
                                    <th scope="col" style="width: 12%;">@lang('Quantity')</th>
                                    <th scope="col" style="width: 15%;">@lang('Price')</th>
                                    <th scope="col" style="width: 20%;">@lang('Tax')</th>
                                    <th scope="col" style="width: 15%;">@lang('Total')</th>
                                    <th scope="col" style="width: 10%;">@lang('Remove')</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              <tbody id="invoice">
                                <tr id="item-row-0">
                                    <td>
                                        <input type="number" step=".01" name="quantity[]" id="item-quantity-0" class="form-control quantity" value="" placeholder="@lang('Quantity')" required>
                                    </td>
                                    <td>
                                        <input type="number" step=".01" name="quantity[]" id="item-quantity-0" class="form-control quantity" value="" placeholder="@lang('Quantity')" required>
                                    </td>
                                    <td>
                                        <input type="number" step=".01" name="price[]" id="item-price-0" class="form-control price" value="" placeholder="@lang('Price')" required>
                                    </td>
                                    <td>
                                        <input type="number" step=".01" name="tax[]" class="form-control tax" value="" placeholder="@lang('Tax')" required>
                                    </td>
                                    <td>
                                        <input type="number" step=".01" name="total[]" class="form-control total" value="0.00" placeholder="@lang('Total')" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info m-add"><i class="fas fa-plus"></i></button>
                                        <button type="button" class="btn btn-info m-remove"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                              <tbody>
                                <tr>
                                    <td colspan="3"></td>
                                    <td style="text-align: right;">@lang('Sub Total')</td>
                                    <td>
                                        <input type="number" step=".01" name="sub_total" class="form-control sub_total" value="{{ old('sub_total', '0.00') }}" placeholder="@lang('Sub Total')" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-right">@lang('Discount')</td>
                                    <td class="text-right">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <input type="number" step=".01" name="discount_percentage" value="{{ old('discount_percentage', '0.00') }}" class="form-control discount_percentage" placeholder="%">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" step=".01" name="total_discount" class="form-control discount" value="{{ old('total_discount', '0.00') }}" placeholder="@lang('Total Discount')">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-right">@lang('Tax')</td>
                                    <td class="text-right">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <input type="number" step=".01" name="tax_percentage" value="{{ old('tax_percentage', '0.00') }}" class="form-control tax_percentage" placeholder="%">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" step=".01" name="total_tax" class="form-control tax" value="{{ old('total_tax', '0.00') }}" placeholder="@lang('Total Tax')">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td style="text-align: right;">@lang('Grand Total')</td>
                                    <td>
                                        <input type="number" step=".01" name="grand_total" class="form-control grand_total" value="{{ old('grand_total', '0.00') }}" placeholder="@lang('Grand Total')" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    "use strict";
    var combo_array = [];
    var d = null;

    $('.js-example-data-ajax').on('select2:select', function (e) {
        var data = e.params.data;
        $("#table-combo").append('<tr data-value="'+ data.id +'" class="table-info" id="tr-removed-id"> <th scope="row">' + data.name + '</th> <td><input type="number" id="bar" min="1" value="1" name="table_combo_quantity[]" style="width : 100px"><input type="hidden" name="table_combo_id[]" value="' + data.id + '" /></td> <td><span class="table-remove"><button type="button" id="table-remove" class="btn btn-info btn-sm my-0"><i class="fas fa-trash"></i></button></span></td> </tr> ')
        // push
        combo_array.push(data.id);
        // blank
        $('.js-example-data-ajax').val(null).trigger('change');
        // array to string
        var b = combo_array.toString();
        var c = b;
        // comma replace to underscore
        window.d = c.replace(/,/g, '_');
    });
</script>

<script type="text/javascript" class="js-code-placeholder">


    $(".js-example-data-ajax").select2({
      ajax: {
        url: "/getItems",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term,
            combo_array: d,
            page: params.page

          };
        },
        processResults: function (data, params) {
          // parse the results into the format expected by Select2

          // since we are using custom formatting functions we do not need to
          // alter the remote JSON data, except to indicate that infinite
          // scrolling can be used
          params.page = params.page || 1;

          return {
            results: data,
            pagination: {
              more: (params.page * 30) < data.total_count
            }
          };
        },
        cache: true
      },
      placeholder: '{{ __('Search Your Item') }}',
      minimumInputLength: 1,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    });

    function formatRepo (repo) {
      if (repo.loading) {
        return repo.text;
      }

      var $container = $(
        "<div class='select2-result-repository clearfix'>" +

          "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'></div>" +
            "</div>" +
          "</div>" +
        "</div>"
      );

      $container.find(".select2-result-repository__title").text(repo.name);
      return $container;
    }

    function formatRepoSelection (repo) {
      return repo.name || repo.sku;
    }

    </script>
@endsection
