@extends('layouts.layout')
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
                        <a href="{{ route('bill.index') }}">@lang('Bills List')</a></li>
                    <li class="breadcrumb-item active">@lang('Add Bill')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Add Bill')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('bill.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vendor_id">@lang('Vendor') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="vendor_id" id="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $key => $value)
                                            <option value="{{ $key }}" {{ old('vendor_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                <label for="billed_at">@lang('Bill Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="billed_at" id="billed_at" class="form-control today-flatpickr" value="{{ old('billed_at') }}" required>
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
                                <label for="name">@lang('Bill Number') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file-signature"></i>
                                    </div>
                                    <input type="text" name="bill_number" value="{{ old('bill_number', $number) }}" id="bill_number" class="form-control @error('bill_number') is-invalid @enderror" required>
                                    @error('bill_number')
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
                                            <option value="{{ $key }}" {{ old('category_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="js-example-data-ajax">@lang('Add Item') </label>
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
                                    @endif
                              </tbody>
                              <tbody>
                                <tr>
                                    <td colspan="3"></td>
                                    <th style="text-align: right;vertical-align: inherit;">@lang('Sub Total')</th>
                                    <td>
                                        <input type="number" step=".01" name="sub_total" class="form-control sub_total" value="{{ old('sub_total', '0.00') }}" placeholder="@lang('Sub Total')" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right">@lang('Tax')</td>
                                    <td>
                                        <input type="number" step=".01" name="total_tax" class="form-control total_tax" value="{{ old('total_tax', '0.00') }}" placeholder="@lang('Total Tax')" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right">@lang('Discount')</td>
                                    <td>
                                        <input type="number" step=".01" name="total_discount" class="form-control total_discount" value="{{ old('total_discount', '0.00') }}" placeholder="@lang('Total Discount')">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Description')</h4></label>
                                <div class="col-md-12">
                                    <div id="input_description" class="@error('description') is-invalid @enderror" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="description" value="{{ old('description') }}" id="description">
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
                                <input type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg btn-block"/>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <a href="{{ route('bill.index') }}" class="btn btn-outline btn-warning btn-lg btn-block" style="float: right;">@lang('Cancel')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.bill.create.js')
@endsection
