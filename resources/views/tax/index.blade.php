@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a href="{{ route('tax.create') }}" class="btn btn-outline btn-info">+ {{ __('tax.add new') }}</a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('tax.tax rates') }}</li>
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
                <h3 class="card-title">{{ __('tax.tax rates') }} </h3>
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
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Tax Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Tax Name')">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Tax Type')</label>
                                        <select class="form-control" name="type">
                                            <option value="">--Select--</option>
                                            <option value="normal" {{ old('type', request()->type) === 'normal' ? 'selected' : ''  }}>@lang('Normal')</option>
                                            <option value="inclusive" {{ old('type', request()->type) === 'inclusive' ? 'selected' : ''  }}>@lang('Inclusive')</option>
                                            <option value="compound" {{ old('type', request()->type) === 'compound' ? 'selected' : ''  }}>@lang('Compound')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('tax.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>{{ __('tax.tax name') }}</th>
                            <th>{{ __('tax.tax rate(%)') }}</th>
                            <th>{{ __('tax.type') }}</th>
                            <th>{{ __('tax.status') }}</th>
                            <th data-orderable="false">{{ __('tax.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $tax)
                            <tr>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->rate }}</td>
                                <td>
                                    @if($tax->type == 'inclusive')
                                        <span class="badge badge-pill badge-primary">@lang('tax.inclusive')</span>
                                    @elseif ($tax->type == 'compound')
                                        <span class="badge badge-pill badge-info">@lang('tax.compound')</span>
                                    @else
                                        <span class="badge badge-pill badge-secondary">@lang('tax.normal')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tax->enabled == '1')
                                        <span class="badge badge-pill badge-success">@lang('tax.enabled')</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">@lang('tax.disabled')</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tax.edit', $tax) }}" class="btn btn-info btn-circle" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    <a href="#" data-href="{{ route('tax.destroy', $tax) }}" class="btn btn-info btn-circle" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $taxes->links() }}
            </div>
      </div>
    </div>
</div>

@include('layouts.delete_modal')
@include('script.tax.index.js')
@endsection

