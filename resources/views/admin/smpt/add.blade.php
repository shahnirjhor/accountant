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
                        <a href="{{ route('smtp.index') }}">{{ __('Smtp List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Add New Smtp') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add New Smtp') }}</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('smtp.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-12"><h4>{{ __('smtp.email') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading  @error('email_address') is-invalid @enderror" name="email_address" value="{{ old('email_address') }}" id="email_address" type="text" placeholder="{{ __('smtp.please type your smtp email') }}" required>
                                    @error('email_address')
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
                                <label class="col-md-12"><h4>{{ __('smtp.host') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-share"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('smtp_host') is-invalid @enderror" name="smtp_host" value="{{ old('smtp_host') }}" id="smtp_host" type="text" placeholder="{{ __('smtp.please type your smtp host') }}" required>
                                    @error('smtp_host')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12"><h4>{{ __('smtp.port') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-arrows-alt-v"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('smtp_port') is-invalid @enderror" name="smtp_port" value="{{ old('smtp_port') }}" id="smtp_port" type="text" placeholder="{{ __('smtp.please type your smtp port') }}" required>
                                    @error('smtp_port')
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
                                <label class="col-md-12"><h4>{{ __('smtp.user') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('smtp_user') is-invalid @enderror" name="smtp_user" value="{{ old('smtp_user') }}" id="smtp_user" type="text" placeholder="{{ __('smtp.please type your smtp user') }}" required>
                                    @error('smtp_user')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12"><h4>{{ __('smtp.password') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('smtp_password') is-invalid @enderror" name="smtp_password" id="smtp_password" type="text" placeholder="{{ __('smtp.please type your smtp password') }}" required>
                                    @error('smtp_password')
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
                                <label class="col-md-12"><h4>{{ __('smtp.type') }}</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-typo3"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('smtp_type') is-invalid @enderror" name="smtp_type" id="smtp_type">
                                        <option value="default" {{ old('smtp_type') == "default" ? 'selected' : '' }}>{{ __('smtp.default') }}</option>
                                        <option value="ssl" {{ old('smtp_type') == "ssl" ? 'selected' : '' }}>{{ __('smtp.ssl') }}</option>
                                        <option value="tls" {{ old('smtp_type') == "tls" ? 'selected' : '' }}>{{ __('smtp.tls') }}</option>
                                    </select>
                                    @error('smtp_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12"><h4>{{ __('smtp.status') }}</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('status') is-invalid @enderror" name="status" id="status">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>{{ __('smtp.active') }}</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>{{ __('smtp.inactive') }}</option>
                                    </select>
                                    @error('status')
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
                            <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('smtp.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('smtp.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
