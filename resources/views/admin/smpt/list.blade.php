@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a href="{{ route('smtp.create') }}" class="btn btn-outline btn-info">+ {{ __('smtp.add new smtp') }}</a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('smtp.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('smtp.smtp list') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

@include('partials.errors')

<div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('smtp.smtp configrution') }}</h3>
        </div>
        <div class="card-body">
            <table id="laravel_datatable" class="table table-striped compact table-width">
                <thead>
                    <tr>
                        <th>{{ __('smtp.id') }}</th>
                        <th>{{ __('smtp.email') }}</th>
                        <th>{{ __('smtp.host') }}</th>
                        <th>{{ __('smtp.port') }}</th>
                        <th>{{ __('smtp.user') }}</th>
                        <th>{{ __('smtp.password') }}</th>
                        <th>{{ __('smtp.type') }}</th>
                        <th>{{ __('smtp.status') }}</th>
                        <th data-orderable="false">{{   __('smtp.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lists as $list)
                        <tr>
                            <td>{{ $list->id }}</td>
                            <td>{{ $list->email_address }}</td>
                            <td>{{ $list->smtp_host }}</td>
                            <td>{{ $list->smtp_port }}</td>
                            <td>{{ $list->smtp_user }}</td>
                            <td>{{ $list->smtp_password }}</td>
                            <td>
                                @if($list->smtp_type == 'ssl')
                                    <span class="badge badge-pill badge-info">@lang('smtp.ssl')</span>
                                @elseif($list->smtp_type == 'tls')
                                    <span class="badge badge-pill badge-success">@lang('smtp.tls')</span>
                                @else
                                    <span class="badge badge-pill badge-secondary">@lang('smtp.default')</span>
                                @endif
                            </td>
                            <td>
                                @if($list->status == 0)
                                    <span class="badge badge-pill badge-secondary">@lang('smtp.inactive')</span>
                                @else
                                    <span class="badge badge-pill badge-primary">@lang('smtp.active')</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('smtp.edit', $list) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="#" data-href="{{ route('smtp.destroy', $list) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $lists->links() }}
        </div>
      </div>
    </div>
</div>

@include('layouts.delete_modal')
@include('script.smtp.js')
@endsection
