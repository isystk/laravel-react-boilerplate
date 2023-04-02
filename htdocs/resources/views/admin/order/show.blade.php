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

<div class="card card-purple">
    <div class="card-body">
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('order.User Name')}}</label>
                <div class="col-sm-4">
                    {{ $order -> user -> name }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('order.Stock Name')}}</label>
                <div class="col-sm-4">
                    {{ $order -> stock -> name }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('order.Quantity')}}</label>
                <div class="col-sm-4">
                    {{ $order -> quantity }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('order.Order Date')}}</label>
                <div class="col-sm-4">
                    {{ $order -> created_at }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
