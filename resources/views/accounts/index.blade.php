@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a href="{{ route('account.create') }}" class="btn btn-outline btn-info">+ {{ __('account.add new account') }}</a>
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('general.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('account.account list') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('account.account list') }}</h3>
                <div class="card-tools">
                    <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                </div>
            </div>
            <div class="card-body">
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>{{ __('account.name') }}</th>
                            <th>{{ __('account.number') }}</th>
                            <th>{{ __('account.current balance') }}</th>
                            <th>{{ __('account.status') }}</th>
                            <th data-orderable="false" data-searchable="false">{{ __('account.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->number }}</td>
                            <td>{{ $account->balance }}</td>
                            <td>
                                @if($account->enabled == '1')
                                    <span class="badge badge-pill badge-success">@lang('category.enabled')</span>
                                @else
                                    <span class="badge badge-pill badge-danger">@lang('category.disabled')</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('account.show', $account) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Show"><i class="fa fa-eye ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="{{ route('account.edit', $account) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="#" data-href="{{ route('account.destroy', $account) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.account.index.js')
@endsection