@extends('layouts.app_admin')

@section('title', '注文ID:' . $order->id)
@php
$menu = 'master';
$subMenu = 'order';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.order.show', $order) }}
@endsection

@section('content')

<div class="card card-purple">
    <!-- card-body -->
    <div class="card-body">

        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">発注名</label>
                <div class="col-sm-4">
                    {{ $order -> user -> name }}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">商品名</label>
                <div class="col-sm-4">
                    {{ $order -> stock -> name }}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">個数</label>
                <div class="col-sm-4">
                    {{ $order -> quantity }}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">発注日時</label>
                <div class="col-sm-4">
                    {{ $order -> created_at }}
                </div>
            </div>
        </div>

    </div>
    <!-- /.card-body -->

</div>

@endsection
