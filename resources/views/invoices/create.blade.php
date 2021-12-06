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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t1" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="white-space: nowrap;">@lang('Account Name')</th>
                                            <th scope="col" style="width: 12%;">@lang('Quantity')</th>
                                            <th scope="col" style="width: 15%;">@lang('Price')</th>
                                            <th scope="col" style="width: 20%;">@lang('Tax')</th>
                                            <th scope="col" style="width: 15%;">@lang('Total')</th>
                                            <th scope="col" style="width: 10%;">@lang('Add / Remove')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice">
                                        <tr id="item-row-0">
                                            <td>
                                                <select id="mySelect2" name="item_name[]"  class="form-control select2" required>
                                                    <option value="">--@lang('Select')--</option>
                                                    @foreach ($items as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        let invoice = $('#invoice').html();

        $(document).on('click', '.m-add', function () {
            alert("rr");
            $('#invoice').append(invoice);
        });

        $(document).on('click', '.m-remove', function () {
            $(this).parent().parent().remove();
        });
    });

    


    $('#mySelect2').on('select2:select', function (e) {
        let applicationName = "{{ $ApplicationSetting->item_name  }}";
        let itemId = $("#mySelect2").val();
        if(itemId) {
            $.post("{{ url('invoice/generateItemData') }}",{itemId},function(data,status) {
                let iQuantity = data.quantity;

                $('#item-quantity-0').val(iQuantity);
                $('#item-price-0').val(10);

                // item-quantity-0
                // alert(iQuantity);
                // $(this).parent().parent().find('.quantity').val(iQuantity);
            });
        }
    });
</script>
@endsection