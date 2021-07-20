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
        <h3 class="card-title">{{__('common.Search Condition')}}</h3>
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
                    <label class="col-sm-2 control-label">{{__('order.User Name')}}</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" size="10" maxlength="100" value="{{ $name }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-secondary">{{__('common.Search')}}</button>
        </div>
    </form>
</div>
<form action="{{ route('admin.order') }}" method="GET" id="pagingForm">
    <input type="hidden" name="name" value="{{ $name }}">
</form>
<div class="row">
    <div class="col-12">
        <div class="card card-purple">
            <div class="card-header">
                <h3 class="card-title">{{__('common.Search Result')}}</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>{{__('order.ID')}}</th>
                            <th>{{__('order.User Name')}}</th>
                            <th>{{__('order.Stock Name')}}</th>
                            <th>{{__('order.Quantity')}}</th>
                            <th>{{__('order.Order Date')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <th>{{ $order->id }}</th>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->stock->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td><a href="{{ route('admin.order.show', ['id'=> $order->id]) }}">{{__('common.Detail')}}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix ">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
