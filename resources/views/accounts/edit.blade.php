@extends('layouts.layout')
@section('one_page_css')
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
                        <a href="{{ route('account.index') }}">{{ __('Account List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit Account') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit Account') }}</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('account.update', $account) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">{{ __('Name') }} <b class="ambitious-crimson">*</b></label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-file-signature"></i>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $account->name) }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Enter Account Name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="number">{{ __('Number') }} <b class="ambitious-crimson">*</b></label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-sort-numeric-up-alt"></i>
                                </div>
                                <input type="text" name="number" value="{{ old('number', $account->number) }}" id="number" class="form-control @error('number') is-invalid @enderror" placeholder="{{ __('Enter Number Code') }}">
                                @error('number')
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
                            <label for="currency_code">{{ __('Currency') }} <b class="ambitious-crimson">*</b></label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-money-check-alt"></i></span>
                                </div>
                                <select class="form-control @error('currency') is-invalid @enderror" name="currency_code" id="currency_code">
                                    @foreach ($currencies as $key => $value)
                                        <option value="{{ $key }}" @if($key == old('currency_code', $account->currency_code)) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="opening_balance">{{ __('Opening Balance') }} <b class="ambitious-crimson">*</b></label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <input type="text" name="opening_balance" value="{{ old('opening_balance', $account->opening_balance) }}" id="opening_balance" class="form-control @error('number') is-invalid @enderror" placeholder="{{ __('Enter Balance') }}">
                                @error('opening_balance')
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
                            <label for="bank_name">{{ __('Bank Name') }}</label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-university"></i>
                                </div>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" placeholder="{{ __('Enter Bank Name') }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="bank_phone">{{ __('Bank Phone') }}</label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i>
                                </div>
                                <input type="text" name="bank_phone" value="{{ old('bank_phone', $account->bank_phone) }}" id="bank_phone" class="form-control @error('bank_phone') is-invalid @enderror" placeholder="{{ __('Enter Bank Phone Number') }}">
                                @error('bank_phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-md-12"><h4>{{ __('Bank Address') }}</h4></label>
                        <div class="col-md-12">
                            <div id="edit_input_address" style="min-height: 55px;">
                            </div>
                            <input type="hidden" name="bank_address" id="bank_address" value="{{ old('bank_address',$account->bank_address) }}">
                            @error('bank_address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="enabled">{{ __('Status') }} <b class="ambitious-crimson">*</b></label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-thermometer-three-quarters"></i></span>
                                </div>
                                <select class="form-control @error('enabled') is-invalid @enderror" name="enabled" id="enabled">
                                    <option value="1" {{ old('enabled', $account->enabled) === 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                    <option value="0" {{ old('enabled', $account->enabled) === 0 ? 'selected' : '' }}>@lang('No')</option>
                                </select>
                                @error('enabled')
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
                        <input type="submit" value="@lang('Update')" class="btn btn-outline btn-info btn-lg"/>
                        <a href="{{ route('account.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";
    $(document).ready(function() {
        var equill = new Quill('#edit_input_address', {
            theme: 'snow'
        });
        var address = $("#bank_address").val();
        equill.clipboard.dangerouslyPasteHTML(address);
        equill.root.blur();
        $('#edit_input_address').on('keyup', function(){
            var edit_input_address = equill.container.firstChild.innerHTML;
            $("#bank_address").val(edit_input_address);
        });
    });
</script>
@endsection
