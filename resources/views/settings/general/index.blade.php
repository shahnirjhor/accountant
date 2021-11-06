@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
@endsection

@push('header')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">

@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>{{ __('general.general setting') }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('general.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('general.settings') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="card">
	<nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('general.company') }}</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">{{ __('general.localisation') }}</a>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">{{ __('general.invoice') }}</a>
        </div>
    </nav>
	<div class="card-body" style="padding-top : 0">
		<section id="tabs" class="project-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.company') }} <b class="ambitious-crimson">*</b></label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-address-card"></i></span>
                                                    </div>
                                                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" placeholder="{{ __('general.enter company name') }}" value="{{ old('company_name',$company->company_name) }}" required>
                                                    @error('company_name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.email') }} <b class="ambitious-crimson">*</b></label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" name="company_email" class="form-control @error('company_email') is-invalid @enderror" placeholder="{{ __('general.enter email address') }}" value="{{ old('company_email',$company->company_email) }}" required>
                                                    @error('company_email')
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
                                                <label for="exampleInputPassword1">{{ __('general.tax number') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                                    </div>
                                                    <input type="text" name="company_tax_number" class="form-control @error('company_tax_number') is-invalid @enderror" placeholder="{{ __('general.enter tax number') }}" value="{{ old('company_tax_number',$company->company_tax_number) }}">
                                                    @error('company_tax_number')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.phone') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror" placeholder="{{ __('general.enter phone number') }}" value="{{ old('company_phone',$company->company_phone) }}">
                                                    @error('company_phone')
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
                                                <label>{{ __('general.logo') }}</label>
                                                <input id="photo" class="dropify" name="company_logo" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="1024K"/>
                                                <small id="name" class="form-text text-muted">{{ __('general.leave blank for remain unchanged') }}</small>
                                                <p>{{ __('general.max size: 1000kb, allowed format: png, jpg, jpeg') }}</p>
                                                @error('company_logo')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('general.address') }} <b class="ambitious-crimson">*</b></label>
                                                <div id="edit_input_address" class="form-control @error('company_address') is-invalid @enderror" style="max-height: 55px;"></div>
                                                <input type="hidden" name="company_address" id="company_address" value="{{ old('company_address',$company->company_address) }}">
                                                @error('company_address')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            <input type="submit" value="{{ __('general.save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">{{ __('general.cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general.localisation') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.financial year start') }} </label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                                    </div>
                                                    <input type="text" name="financial_start" id="financial_start" class="form-control @error('financial_start') is-invalid @enderror" placeholder="{{ __('general.enter financial year start') }}" value="{{ old('financial_start',$company->financial_start) }}">
                                                    @error('financial_start')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.time zone') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                    </div>
                                                    <select id="timezone" name="timezone" class="form-control select2 @error('timezone') is-invalid @enderror">
                                                        @foreach($timezone as $key => $value)
                                                            <option value="{{ $key }}" {{ old('timezone', $company->timezone) == $key ? 'selected' : '' }} >{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('timezone')
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
                                                <label for="exampleInputPassword1">{{ __('general.date format') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <select class="form-control @error('date_format') is-invalid @enderror" autocomplete="off" id="date_format" name="date_format">
                                                        <option value="d M Y" {{ old('date_format', $company->date_format) == "d M Y" ? 'selected' : '' }}>{{ date('d M Y') }}</option>
                                                        <option value="d F Y" {{ old('date_format', $company->date_format) == "d F Y" ? 'selected' : '' }}>{{ date('d F Y') }}</option>
                                                        <option value="d m Y" {{ old('date_format', $company->date_format) == "d m Y" ? 'selected' : '' }}>{{ date('d m Y') }}</option>
                                                        <option value="m d Y" {{ old('date_format', $company->date_format) == "m d Y" ? 'selected' : '' }}>{{ date('m d Y') }}</option>
                                                        <option value="Y m d" {{ old('date_format', $company->date_format) == "Y m d" ? 'selected' : '' }}>{{ date('Y m d') }}</option>
                                                    </select>
                                                    @error('date_format')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('general.date separator') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-minus"></i></span>
                                                    </div>
                                                    <select class="form-control @error('date_separator') is-invalid @enderror" id="date_separator" name="date_separator">
                                                        <option value="dash" {{ old('date_separator', $company->date_separator) == "dash" ? 'selected' : '' }}>{{ __('general.dash') }} (-)</option>
                                                        <option value="slash" {{ old('date_separator', $company->date_separator) == "slash" ? 'selected' : '' }}>{{ __('general.slash') }} (/)</option>
                                                        <option value="dot" {{ old('date_separator', $company->date_separator) == "dot" ? 'selected' : '' }}>{{ __('general.dot') }} (.)</option>
                                                        <option value="comma" {{ old('date_separator', $company->date_separator) == "comma" ? 'selected' : '' }}>{{ __('general.comma') }} (,)</option>
                                                        <option value="space" {{ old('date_separator', $company->date_separator) == "space" ? 'selected' : '' }}>{{ __('general.space') }} ( )</option>
                                                    </select>
                                                    @error('date_separator')
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
                                                <label for="exampleInputPassword1">{{ __('general.percent (%) position') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                                    </div>
                                                    <select class="form-control @error('percent_position') is-invalid @enderror" id="percent_position" name="percent_position">
                                                        <option value="before" {{ old('percent_position', $company->percent_position) == "before" ? 'selected' : '' }}>{{ __('general.before number') }}</option>
                                                        <option value="after" {{ old('percent_position', $company->percent_position) == "after" ? 'selected' : '' }}>{{ __('general.after number') }}</option>
                                                    </select>
                                                    @error('percent_position')
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
                                            <input type="submit" value="{{ __('general.save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">{{ __('general.cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general.invoice') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.number prefix') }} </label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                  </div>
                                                  <input type="text" name="invoice_number_prefix" id="invoice_number_prefix" class="form-control" placeholder="{{ __('general.enter number prefix') }}" value="{{ $company->invoice_number_prefix ?? null }}">
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.number digit') }}</label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width"></i></span>
                                                  </div>
                                                  <input type="text" name="invoice_number_digit" id="invoice_number_digit" class="form-control" placeholder="{{ __('general.enter number digit') }}" value="{{ $company->invoice_number_digit ?? null }}">
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.next number') }}</label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-chevron-right"></i></span>
                                                  </div>
                                                  <input type="text" name="invoice_number_next" id="invoice_number_next" class="form-control" placeholder="{{ __('general.enter next number') }}" value="{{ $company->invoice_number_next ?? null }}">
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('general.item name') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <select class="form-control @error('invoice_item') is-invalid @enderror" autocomplete="off" id="invoice_item" name="invoice_item">
                                                    @foreach($itemNames as $key => $value)
                                                        <option value="{{ $key }}" {{ old('invoice_item', $company->invoice_item) == $key ? 'selected' : '' }}>{{ ucwords(str_replace('settings.invoice.', '', $value ?? null)) }}</option>
                                                    @endforeach
                                                    </select>
                                                  {{-- <input type="text" name="invoice_item" id="invoice_item" class="form-control" placeholder="{{ __('general.enter item name') }}" value="{{ str_replace('settings.invoice.', '', $company->invoice_item ?? null)}}"> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.price name') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <select class="form-control @error('invoice_price') is-invalid @enderror" autocomplete="off" id="invoice_price" name="invoice_price">
                                                    @foreach($priceNames as $key => $value)
                                                        <option value="{{ $key }}" {{ old('invoice_price', $company->invoice_price) == $key ? 'selected' : '' }} >{{ ucwords(str_replace('settings.invoice.', '', $value ?? null)) }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('general.quantity name') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <input type="text" name="invoice_quantity" id="invoice_quantity" class="form-control" placeholder="{{ __('general.enter quantity name') }}" value="{{ str_replace('settings.invoice.', '', $company->invoice_quantity ?? null) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>{{ __('general.logo') }}</label>
                                              
                                                <input id="photo1" class="dropify" name="invoice_logo" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="1024K"/>
                                                <small id="name" class="form-text text-muted">{{ __('general.leave blank for remain unchanged') }}</small>
                                                <p>{{ __('general.max size: 1000kb, allowed format: png, jpg, jpeg') }}</p>
                                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            <input type="submit" value="{{ __('general.save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">{{ __('general.cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </section>
    </div>
</div>

@include('script.general.js')

<script>
    $(document).ready( function () {
        if($('#sku_type').val() == '1') {
            $('#sku_random').show(500);
            $('#sku_define').hide(500);
        } else {
            $('#sku_random').hide(500);
            $('#sku_define').show(500);
        }
        $('#sku_type').change(function(){
            if($('#sku_type').val() == '1') {
                $('#sku_random').show(500);
                $('#sku_define').hide(500);
            } else {
                $('#sku_random').hide(500);
                $('#sku_define').show(500);
            }
        });
    });
</script>

@endsection
