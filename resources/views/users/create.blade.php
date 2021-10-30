@extends('layouts.layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('users.user list') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('users.create user') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('users.create user') }}</h3>
            </div>
            <div class="card-body">
                <form id="userQuickForm" class="form-material form-horizontal" action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>{{ __('users.name') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" type="text" placeholder="{{ __('users.type your name here') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>{{ __('users.email') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="email" type="email" placeholder="{{ __('users.type your email here') }}" required>
                                    @error('email')
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
                                <label class="col-md-12 col-form-label"><h4>{{ __('users.password') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('password') is-invalid @enderror" name="password" id="password" type="password" placeholder="{{ __('users.type your password here') }}" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>{{ __('users.confirm password') }} <b class="ambitious-crimson">*</b></h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" type="password" placeholder="{{ __('users.type your confirm password here') }}" required>
                                    @error('password_confirmation')
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
                                <label class="col-md-12 col-form-label"><h4>{{ __('users.user for') }}</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('role_for') is-invalid @enderror" name="role_for" id="role_for">
                                        <option value="0" {{ old('role_for') == 0 ? 'selected' : '' }}>{{ __('roles.system user') }}</option>
                                        <option value="1" {{ old('role_for') == 1 ? 'selected' : '' }}>{{ __('roles.general user') }}</option>
                                    </select>
                                    @error('role_for')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>{{ __('users.phone') }}</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input class="form-control ambitious-form-loading @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" id="phone" type="text" placeholder="{{ __('users.type phone number here') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="staff_block">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label"><h4>{{ __('users.staff role') }}</h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        </div>
                                        <select class="form-control ambitious-form-loading @error('staff_roles') is-invalid @enderror" name="staff_roles" id="staff_roles">
                                            @foreach($staffRoles as $key => $role)
                                                <option value="{{$key}}" {{ old('staff_roles') == $key ? 'selected' : '' }}>{{$role}}</option>
                                            @endforeach
                                        </select>
                                        @error('staff_roles')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label"><h4>{{ __('users.staff company') }}</h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <select class="form-control select2bs4 @error('staff_company') is-invalid @enderror" id="staff_company" name="staff_company" data-placeholder="Select a company">
                                            @foreach ($companies as $value)
                                                <option value="{{ $value->id }}" {{ old('staff_company') == $value->id ? 'selected' : '' }} >{{ $value->company_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('staff_company')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="user_block">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label"><h4>{{ __('users.user role') }}</h4></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        </div>
                                        <select class="form-control ambitious-form-loading @error('user_roles') is-invalid @enderror" name="user_roles" id="user_roles">
                                            @foreach($userRoles as $key => $role)
                                                <option value="{{$key}}" {{ old('user_roles') == $key ? 'selected' : '' }}>{{$role}}</option>
                                            @endforeach
                                        </select>
                                        @error('user_roles')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label"><h4>{{ __('users.user company') }}</h4></label>
                                    <div class="input-group mb-3">
                                        <select class="select2 select2-primary @error('user_company') is-invalid @enderror" id="user_company" name="user_company[]" multiple="multiple" style="width: 100%;" data-placeholder="Select a company">
                                            @foreach ($companies as $value)
                                                <option value="{{ $value->id }}" @if(is_array(old('user_company')) && in_array($value->id, old('user_company'))) selected @endif>{{ $value->company_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_company')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12 col-form-label"><h4>{{ __('users.photo') }}</h4></label>
                            <div class="col-md-12">

                                <input id="photo" class="dropify" name="photo" value="{{ old('photo') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2024K" />
                                <p>{{ __('users.max size: 2mb, allowed format: png, jpg, jpeg') }}</p>
                            </div>
                            @if ($errors->has('photo'))
                                <div class="error ambitious-red">{{ $errors->first('photo') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12 col-form-label"><h4>{{ __('users.address') }}</h4></label>
                            <div class="col-md-12">
                                <div id="input_address" class="@error('address') is-invalid @enderror" style="min-height: 55px;">
                                </div>
                                <input type="hidden" name="address" value="{{ old('address') }}" id="address">
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>{{ __('users.status') }}</h4></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('status') is-invalid @enderror" required="required" name="status" id="status">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>{{ __('users.active') }}</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>{{ __('users.inactive') }}</option>
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
                    <br><br>
                    <div class="form-group">
                        <label class="col-md-3 col-form-label"></label>
                        <div class="col-md-8">
                            <input type="submit" value="{{ __('users.submit') }}" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('users.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('users.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    @include('script.users.create.js')
@endsection
