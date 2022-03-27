@extends('layouts.layout')
@section('one_page_js')
    <!-- Include the Quill library -->
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/swal.js') }}"></script>

@endsection

@section('one_page_css')
    <!-- Include quill -->
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/select2/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
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
                    <li class="breadcrumb-item active">@lang('Edit Invoice')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Edit Invoice')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('invoice.update', $invoice) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                            <option value="{{ $key }}" {{ old('customer_id', $invoice->customer_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                            <option value="{{ $key }}" {{ old('currency_code', $invoice->currency_code) == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                    <input type="text" name="invoiced_at" id="invoiced_at" class="form-control today-flatpickr" value="{{ old('invoiced_at', $invoice->invoiced_at) }}" required>
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
                                    <input type="text" name="due_at" id="due_at" class="form-control flatpickr" value="{{ old('due_at', $invoice->due_at) }}" required>
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
                                    <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" id="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" required readonly>
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
                                    <input type="text" name="order_number" value="{{ old('order_number', $invoice->order_number) }}" id="order_number" class="form-control @error('order_number') is-invalid @enderror">
                                    @error('order_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">@lang('Category')</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-code-branch"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="category_id" id="category_id">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $key => $value)
                                            <option value="{{ $key }}" {{ old('category_id', $invoice->category_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="js-example-data-ajax">{{ __('Add Item') }} </label>
                            <div class="form-group input-group" style="margin-bottom: unset;">
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
                            <table class="table" id="table-combo">
                              <thead>
                                <tr class="bg-info">
                                    <th scope="col" style="white-space: nowrap;">@lang('Item Name')</th>
                                    <th scope="col" style="width: 12%;">@lang('Quantity')</th>
                                    <th scope="col" style="width: 15%;">@lang('Price')</th>
                                    <th scope="col" style="width: 20%;">@lang('Tax')</th>
                                    <th scope="col" style="width: 15%;">@lang('Total')</th>
                                    <th scope="col" style="width: 10%;">@lang('Remove')</th>
                                </tr>
                              </thead>
                              <tbody>
                                    @if (old('product.order_row_id'))
                                        @foreach (old('product.order_row_id') as $key => $value)
                                        @php
                                            $showTaxTypeRate = "";
                                            if(old('product.order_tax_type_rate')[$key] == '0') {
                                                $showTaxTypeRate = "No Tax";
                                            } else {
                                                $taxTypeRate = old('product.order_tax_type_rate')[$key];
                                                $taxTypeRate = explode("_",$taxTypeRate);
                                                $showTaxTypeRate = $taxTypeRate[1]."% ".ucfirst($taxTypeRate[0]);
                                            }
                                        @endphp
                                        <tr id="{{ old('product.order_row_id')[$key] }}" class="table-info">
                                            <th scope="row">
                                                <input type="hidden" class="order_row_id" value="{{ old('product.order_row_id')[$key] }}" name="product[order_row_id][]">
                                                <input type="hidden" class="order_name" value="{{ old('product.order_name')[$key] }}" name="product[order_name][]">{{ old('product.order_name')[$key] }}
                                            </th>
                                            <td>
                                                <input type="number" step="any" class="form-control order_quantity" min="1" value="{{ old('product.order_quantity')[$key] }}" name="product[order_quantity][]">
                                            </td>
                                            <td>
                                                <input type="hidden" class="order_price" value="{{ old('product.order_price')[$key] }}" name="product[order_price][]"><span>{{ old('product.order_price')[$key] }}</span>
                                            </td>
                                            <td>
                                                <input type="hidden" class="order_tax_type_rate" value="{{ old('product.order_tax_type_rate')[$key] }}" name="product[order_tax_type_rate][]">
                                                <input type="hidden" class="order_product_tax" value="{{ old('product.order_product_tax')[$key] }}" name="product[order_product_tax][]"><span class="order_product_tax_text">{{ $showTaxTypeRate }}</span>
                                            </td>
                                            <td>
                                                <input type="hidden" class="order_subtotal" value="{{ old('product.order_subtotal')[$key] }}" name="product[order_subtotal][]"><span class="order_subtotal_text">{{ old('product.order_subtotal')[$key] }}</span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-info btn-outline table-remove" data-toggle="modal" data-target="#myModal" title="Delete">
                                                  <i class="fa fa-trash ambitious-padding-btn"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        @foreach ($invoice->items as $item)
                                        @php
                                            $showTaxTypeRate = "No Tax";
                                            $tax_type_rate = "0";
                                            if($item->tax_id) {
                                                $cTax = $item->item->tax;
                                                $showTaxTypeRate = $cTax->name;
                                                $tax_type_rate = $cTax->type."_".$cTax->rate;
                                            }
                                        @endphp
                                            <tr id="{{ $item->item_id }}" class="table-info">
                                                <th scope="row">
                                                    <input type="hidden" class="order_row_id" value="{{ $item->item_id }}" name="product[order_row_id][]">
                                                    <input type="hidden" class="order_name" value="{{ $item->name }}" name="product[order_name][]">{{ $item->name }}
                                                </th>
                                                <td>
                                                    <input type="number" step="any" class="form-control order_quantity" min="1" value="{{ $item->quantity }}" name="product[order_quantity][]">
                                                </td>
                                                <td>
                                                    <input type="hidden" class="order_price" value="{{ $item->price }}" name="product[order_price][]"><span>{{ $item->price }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" class="order_tax_type_rate" value="{{ $tax_type_rate }}" name="product[order_tax_type_rate][]">
                                                    <input type="hidden" class="order_product_tax" value="{{ $item->tax }}" name="product[order_product_tax][]"><span class="order_product_tax_text">{{ $showTaxTypeRate }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" class="order_subtotal" value="{{ $item->total }}" name="product[order_subtotal][]"><span class="order_subtotal_text">{{ number_format($item->total, 2) }}</span>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-info btn-outline table-remove" data-toggle="modal" data-target="#myModal" title="Delete">
                                                      <i class="fa fa-trash ambitious-padding-btn"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                @php
                                    $cSubTotal = 0.00;
                                    $cTaxAmount = 0.00;
                                    $cDiscount = 0.00;
                                    $cTotal = 0.00;
                                @endphp
                                @foreach ($invoice->totals as $total)
                                    @if ($total->code == 'sub_total')
                                        @php
                                            $cSubTotal = $total->amount;
                                        @endphp
                                    @endif
                                    @if ($total->code == 'tax')
                                        @php
                                            $cTaxAmount = $cTaxAmount + $total->amount;
                                        @endphp
                                    @endif
                                    @if ($total->code == 'discount')
                                        @php
                                            $cDiscount = $total->amount;
                                        @endphp
                                    @endif
                                    @if ($total->code == 'total')
                                        @php
                                            $cTotal = $total->amount;
                                        @endphp
                                    @endif
                                @endforeach
                                <tbody>
                                    <tr>
                                        <td colspan="3"></td>
                                        <th style="text-align: right;vertical-align: inherit;">@lang('Sub Total')</th>
                                        <td>
                                            <input type="number" step=".01" name="sub_total" class="form-control sub_total" value="{{ number_format($cSubTotal, 2) }}" placeholder="@lang('Sub Total')" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-right">@lang('Tax')</td>
                                        <td>
                                            <input type="number" step=".01" name="total_tax" class="form-control total_tax" value="{{ number_format($cTaxAmount, 2) }}" placeholder="@lang('Total Tax')" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-right">@lang('Discount')</td>
                                        <td>
                                            <input type="number" step=".01" name="total_discount" class="form-control total_discount" value="{{ number_format($cDiscount, 2) }}" placeholder="@lang('Total Discount')">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td style="text-align: right;">@lang('Grand Total')</td>
                                        <td>
                                            <input type="number" step=".01" name="grand_total" class="form-control grand_total" value="{{ number_format($cTotal, 2) }}" placeholder="@lang('Grand Total')" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Description')</h4></label>
                                <div class="col-md-12">
                                    <div id="input_description" class="@error('description') is-invalid @enderror" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="description" value="{{ old('description', $invoice->notes) }}" id="description">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Picture')</h4></label>
                                <div class="col-md-12">
                                    <input id="picture" class="dropify" name="picture" value="{{ old('picture') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2024K" />
                                    <small id="name" class="form-text text-muted">@lang('Leave Blank For Remain Unchanged')</small>
                                    <p>@lang('Max Size: 2mb, Allowed Format: png, jpg, jpeg')</p>
                                </div>
                                @if ($errors->has('picture'))
                                    <div class="error ambitious-red">{{ $errors->first('picture') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" value="@lang('Update')" class="btn btn-outline btn-info btn-lg btn-block"/>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <a href="{{ route('invoice.index') }}" class="btn btn-outline btn-warning btn-lg btn-block" style="float: right;">@lang('Cancel')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.invoice.edit.js')
@endsection
