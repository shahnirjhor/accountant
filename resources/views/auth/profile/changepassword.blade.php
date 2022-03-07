@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('entire.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('entire.change password') }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('entire.change password') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal" action="{{ route('profile.updatePassword') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('entire.current password') }} <b class="ambitious-crimson"></b></h4>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading" name="current-password" id="current-password" type="password" placeholder="{{ __('entire.type your current password here') }}">
                            </div>
                            @if ($errors->has('current-password'))
                                {{ Session::flash('error',$errors->first('current-password')) }}
                            @endif
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('entire.new password') }} <b class="ambitious-crimson"></b></h4>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading" name="new-password" id="new-password" type="password" placeholder="{{ __('entire.type your new password here') }}">
                                <small id="name" class="form-text text-muted">{{ __('entire.6 characters long') }}</small>
                            </div>
                            @if ($errors->has('new-password'))
                                {{ Session::flash('error',$errors->first('new-password')) }}
                            @endif
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('entire.confirm password') }} <b class="ambitious-crimson"></b></h4>
                            </label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading" name="new-password_confirmation" id="new-password-confirm" type="password" placeholder="{{ __('entire.type your confirm password here') }}">
                                <small id="name" class="form-text text-muted">{{ __('entire.6 characters long') }}</small>
                            </div>
                            @if ($errors->has('new-password_confirmation'))
                                {{ Session::flash('error',$errors->first('new-password_confirmation')) }}
                            @endif
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-info btn-lg"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
