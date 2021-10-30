@extends('layouts.layout')
@section('one_page_js')
<!-- Include the Quill library -->
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
@endsection

@section('one_page_css')
    <!-- Include quill -->
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('entire.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('entire.account setting title') }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('entire.account setting title') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal" action="{{ route('profile.updateSetting') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('entire.name') }} <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading" name="name" id="name" value="{{ $user->name }}" type="text" placeholder="{{ __('entire.type your name here') }}" required>
                            </div>
                            @if ($errors->has('name'))
                                {{ Session::flash('error',$errors->first('name')) }}
                            @endif
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('entire.email') }} <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading" name="email" id="email" value="{{ $user->email }}" type="email" placeholder="{{ __('entire.type your email here') }}" required>
                            </div>
                            @if ($errors->has('email'))
                                {{ Session::flash('error',$errors->first('email')) }}
                            @endif
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center"><h4>{{ __('entire.photo') }} </h4></label>
                            <div class="col-md-9">
                                {{ __('entire.max dimension: 200 x 200, max size: 100kb, allowed format: png') }}
                                <input id="photo" class="dropify" name="photo" value="{{ old('photo') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="100K"/><small id="name" class="form-text text-muted">{{ __('entire.leave blank for remain unchanged') }}</small>
                            </div>

                            @if ($errors->has('photo'))
                                <div class="error ambitious-red">{{ $errors->first('photo') }}</div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center"><h4>{{ __('entire.phone') }}</h4></label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading" name="phone" value="{{ $user->phone }}" id="phone" type="text" placeholder="{{ __('entire.type your phone here') }}">
                            </div>
                            @if ($errors->has('phone'))
                                {{ Session::flash('error',$errors->first('phone')) }}
                            @endif
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center"><h4>{{ __('entire.address') }}</h4></label>
                            <div class="col-md-8">
                                <div id="edit_input_address">
                                </div>
                                <input type="hidden" name="address" id="address" value="{{ $user->address }}">
                            </div>
                            @if ($errors->has('address'))
                                {{ Session::flash('error',$errors->first('address')) }}
                            @endif
                        </div>
                    </div>
                    <br>

                    <div class="card-footer">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="{{ __('entire.submit') }}" class="btn btn-outline btn-info btn-lg"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('script.setting.js')
@endsection
