@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('css/switch.css') }}" rel="stylesheet">
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('currency.index') }}">{{ __('currency.currency list') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('currency.add new currency') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('currency.add new currency') }}</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('currency.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">{{ __('currency.name') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-money-check-alt"></i></span>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('currency.enter currency name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="code">{{ __('currency.code') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-code"></i></span>
                                    </div>
                                    <select class="form-control @error('code') is-invalid @enderror" required="required" id="code" name="code">
                                        <option value="">- {{ __('currency.select currency code') }} -</option>
                                        @foreach($data as $key=> $value)
                                            <option value="{{ $key }}" {{ old('code') == $key ? 'selected' : '' }}>{{ $key }}</option>
                                        @endforeach
                                    </select>
                                    @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="rate">{{ __('currency.rate') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-greater-than-equal"></i></span>
                                    </div>
                                    <input type="text" id="rate" name="rate" value="{{ old('rate') }}" class="form-control @error('rate') is-invalid @enderror" placeholder="{{ __('currency.enter currency rate') }}" required>
                                    @error('rate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="precision">{{ __('currency.precision') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                                    </div>
                                    <input type="text" name="precision" value="{{ old('precision') }}" class="form-control @error('precision') is-invalid @enderror" placeholder="{{ __('currency.enter precision') }}" required>
                                    @error('precision')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="symbol">{{ __('currency.symbol') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input class="form-control @error('symbol') is-invalid @enderror" placeholder="{{ __('currency.enter symbol') }}" required="required" name="symbol" value="{{ old('symbol') }}" type="text" id="symbol" required>
                                    @error('symbol')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="symbol_first">{{ __('currency.symbol position') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-crosshairs"></i></span>
                                    </div>
                                    <select class="form-control @error('symbol_first') is-invalid @enderror" required="required" id="symbol_first" name="symbol_first">
                                        <option value="1" {{ old('symbol_first') == 1 ? 'selected' : '' }}>{{ __('currency.before amount') }}</option>
                                        <option value="0" {{ old('symbol_first') == 0 ? 'selected' : '' }}>{{ __('currency.after amount') }}</option>
                                    </select>
                                    @error('symbol_first')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="decimal_mark">{{ __('currency.decimal mark') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                    </div>
                                    <input class="form-control @error('decimal_mark') is-invalid @enderror" placeholder="{{ __('currency.enter decimal mark') }}" required="required" name="decimal_mark" value="{{ old('decimal_mark') }}" type="text" id="decimal_mark" required>
                                    @error('decimal_mark')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="thousands_separator">{{ __('currency.thousands separator') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                    </div>
                                    <input class="form-control @error('thousands_separator') is-invalid @enderror" placeholder="{{ __('currency.enter thousands separator') }}" name="thousands_separator" value="{{ old('thousands_separator') }}" type="text" id="thousands_separator" required>
                                    @error('thousands_separator')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="enabled">{{ __('brand.enabled') }} <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('enabled') is-invalid @enderror" required="required" name="enabled" id="enabled">
                                        <option value="1" {{ old('enabled') == 1 ? 'selected' : '' }}>{{ __('tax.yes') }}</option>
                                        <option value="0" {{ old('enabled') == 0 ? 'selected' : '' }}>{{ __('tax.no') }}</option>
                                    </select>
                                    @error('enabled')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="default_currency">{{ __('brand.default currency') }} </label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-typo3"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('default_currency') is-invalid @enderror" required="required" name="default_currency" id="default_currency">
                                        <option value="1" {{ old('default_currency') == 1 ? 'selected' : '' }}>{{ __('tax.yes') }}</option>
                                        <option value="0" {{ old('default_currency') == 0 ? 'selected' : '' }}>{{ __('tax.no') }}</option>
                                    </select>
                                    @error('default_currency')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-form-label"></label>
                        <div class="col-md-8">
                            <input type="submit" value="{{ __('entire.submit') }}" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('currency.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('currency.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('script.currency.create.js')

@endsection