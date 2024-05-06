@extends('layouts.app_admin')

@section('title', __('order.Order List'))
@php
    $menu = 'master';
    $subMenu = 'order';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.order') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.order') }}" method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="form-group">
                    <div class="control-group" id="orderName">
                        <label class="col-sm-2 control-label">{{ __('order.User Name') }}</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                name="name"
                                value="{{ $request->name }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.users.name') }}"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group" id="orderName">
                        <label class="col-sm-2 control-label">{{ __('order.Order Date') }}</label>
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <input
                                        type="text"
                                        name="order_date_from"
                                        value="{{ $request->order_date_from }}"
                                        class="form-control date-picker"
                                        maxlength="{{ config('const.maxlength.commons.date') }}"
                                    />
                                </div>
                                <span class="pt-2">～</span>
                                <div class="col">
                                    <input
                                        type="text"
                                        name="order_date_to"
                                        value="{{ $request->order_date_to }}"
                                        class="form-control date-picker"
                                        maxlength="{{ config('const.maxlength.commons.date') }}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.order') }}" method="GET" id="pagingForm">
        <input type="hidden" name="name" value="{{ $request->name }}">
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.Search Result') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                @include('admin.common.sortablelink_th', ['params' => ['id', __('order.ID')]])
                                @include('admin.common.sortablelink_th', ['params' => ['users.name', __('order.User Name')]])
                                @include('admin.common.sortablelink_th', ['params' => ['sum_price', __('order.Sum Price')]])
                                @include('admin.common.sortablelink_th', ['params' => ['created_at', __('order.Order Date')]])
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <th>{{ $order->id }}</th>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->sum_price }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>
                                    <a
                                        class="btn btn-info btn-sm"
                                        href="{{ route('admin.order.show', ['order'=> $order]) }}"
                                    >{{ __('common.Detail') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $orders->links('admin.common.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
