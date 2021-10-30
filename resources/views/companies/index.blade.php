@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                    <h3><a href="{{ route('company.create') }}" class="btn btn-outline btn-info">+ {{ __('company.add company') }}</a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('general.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('company.company list') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('company.company list') }}</h3>
                    <div class="card-tools">
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if(request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Company Name')</label>
                                            <input type="text" name="company_name" class="form-control" value="{{ request()->company_name }}" placeholder="@lang('Company Name')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Company Domain')</label>
                                            <input type="text" name="company_domain" class="form-control" value="{{ request()->company_domain }}" placeholder="@lang('Company Domain')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Company Email')</label>
                                            <input type="text" name="company_email" class="form-control" value="{{ request()->company_email }}" placeholder="@lang('Company Email')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">Submit</button>
                                        @if(request()->isFilterActive)
                                            <a href="{{ route('company.index') }}" class="btn btn-secondary">Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped compact table-width" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>{{ __('company.id') }}</th>
                                <th>{{ __('company.name') }}</th>
                                <th>{{ __('company.domain') }}</th>
                                <th>{{ __('company.email') }}</th>
                                <th>{{ __('company.created') }}</th>
                                <th>{{ __('company.status') }}</th>
                                <th>{{ __('company.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td>{{ $company->id }}</td>
                                    <td>{{ $company->company_name }}</td>
                                    <td>{{ $company->domain }}</td>
                                    <td>{{ $company->company_email }}</td>
                                    <td>{{ date($company->date_format, strtotime($company->created_at)) }}</td>
                                    <td>
                                        @if($company->enabled == '1')
                                            <span class="badge badge-pill badge-success">@lang('company.enabled')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('company.disabled')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href=" {{ route('company.edit', $company) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                        <a href="#" data-href="{{ route('company.destroy', $company) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>

@include('layouts.delete_modal')
@include('script.company.index.js')
@endsection
