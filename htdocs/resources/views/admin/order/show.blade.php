@extends('layouts.app_admin')

@section('title', '注文履歴詳細')
@php
$menu = 'master';
$subMenu = 'order';
@endphp

@section('content')

<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
              <h1>注文ID:{{$order->id}}</h1>
          </div>
          <div class="col-sm-6">
              {{ Breadcrumbs::render('admin.order.show', $order) }}
          </div>
      </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">

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

            </div>
        </div>

    </div>
</div>
<!-- /.content -->

@endsection
