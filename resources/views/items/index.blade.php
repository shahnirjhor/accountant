@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a href="{{ route('item.create') }}" class="btn btn-outline btn-info">+ {{ __('items.add new item') }}</a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('general.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('items.item list') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('items.items') }} </h3>
                <div class="card-tools">
                    <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                </div>
            </div>
            <div class="card-body">
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>{{ __('items.picture') }}</th>
                            <th>{{ __('currency.name') }}</th>
                            <th>{{ __('items.category') }}</th>
                            <th>{{ __('items.quantity') }}</th>
                            <th>{{ __('items.sale price')}}</th>
                            <th>{{ __('items.purchase price')}}</th>
                            <th>{{ __('items.status') }}</th>
                            <th data-orderable="false">{{ __('currency.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                        <tr>
                            <td><img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/'.$item->picture) }}" alt="" /></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->sale_price }}</td>
                            <td>{{ $item->purchase_price }}</td>
                            <td>
                                @if($item->enabled == '1')
                                    <span class="badge badge-pill badge-success">@lang('category.enabled')</span>
                                @else
                                    <span class="badge badge-pill badge-danger">@lang('category.disabled')</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('item.edit', $item) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="#" data-href="{{ route('item.destroy', $item) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.items.index.js')
@endsection