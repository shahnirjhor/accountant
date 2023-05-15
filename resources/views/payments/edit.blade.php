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
                        <a href="{{ route('payment.index') }}">@lang('Payment List')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Payment')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Edit Payment')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('payment.update', $payment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="paid_at">@lang('Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="paid_at" id="paid_at" class="form-control flatpickr" value="{{ old('paid_at', $payment->paid_at) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="amount">@lang('Amount') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                    <input id="amount" class="form-control" name="amount" type="text" value="{{ old('amount',$payment->amount) }}" placeholder="@lang('Enter Amount')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="account_id">@lang('Account') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-university"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="account_id" id="account_id" required>
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $key => $value)
                                            <option value="{{ $key }}" {{ old('account_id',$payment->account_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="vendor_id">@lang('Vendor') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="vendor_id" id="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $key => $value)
                                            <option value="{{ $key }}" {{ old('vendor_id',$payment->vendor_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">@lang('Category') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-folder"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="category_id" id="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $key => $value)
                                            <option value="{{ $key }}" {{ old('category_id',$payment->category_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method">@lang('Payment Method') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-credit-card"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="payment_method" id="payment_method" required>
                                        @foreach ($payment_methods as $key => $value)
                                            <option value="{{ $key }}" {{ old('payment_method',$payment->payment_method) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Description')</h4></label>
                                <div class="col-md-12">
                                    <div id="input_description" class="@error('description') is-invalid @enderror" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="description" value="{{ old('description',$payment->description) }}" id="description">
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
                                <label class="col-md-12 col-form-label"><h4>@lang('Attachment')</h4></label>
                                <div class="col-md-12">
                                    <input id="attachment" class="dropify" name="attachment" value="{{ old('attachment') }}" type="file" data-allowed-file-extensions="png jpg jpeg pdf" data-max-file-size="2024K" />
                                    <p>@lang('Max Size: 2mb, Allowed Format: pdf, png, jpg, jpeg')</p>
                                </div>
                                @if ($errors->has('attachment'))
                                    <div class="error ambitious-red">{{ $errors->first('attachment') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input id="currency_code" name="currency_code" type="hidden" value="{{ $payment->currency_code }}" required>
                    <input id="currency_rate" name="currency_rate" type="hidden" value="{{ $payment->currency_rate }}" required>
                    <div class="form-group">
                        <label class="col-md-3 col-form-label"></label>
                        <div class="col-md-8">
                            <input type="submit" value="@lang('Update')" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('payment.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.payments.edit.js')
@endsection
