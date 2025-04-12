@extends('layouts.app_admin')

@section('title', __('order.Order ID:') . $order->id)
@php
    $menu = 'master';
    $subMenu = 'order';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.order.show', $order) }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.order') }}">{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('order.User Name') }}</label>
                    <div class="col-sm-4">
                        {{ $order->user->name }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('order.Sum Price') }}</label>
                    <div class="col-sm-4">
                        {{ $order->sum_price }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('order.Order Date') }}</label>
                    <div class="col-sm-4">
                        {{ $order->created_at }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>{{ __('order.Stock Name') }}</th>
                            <th>{{ __('order.Price') }}</th>
                            <th>{{ __('order.Quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orderStocks as $orderStock)
                        <tr>
                            <th>{{ $orderStock->stock->name }}</th>
                            <td>{{ $orderStock->price }}</td>
                            <td>{{ $orderStock->quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
