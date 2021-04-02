@extends('layouts.app_admin')

@section('title', '注文履歴一覧')
@php
$menu = 'master';
$subMenu = 'order';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => '注文履歴一覧',
  'breadcrumbs' => (object) [
    (object) [
      'label'   => '注文履歴一覧'
    ]
  ]
])

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">

                <div class="card card-purple">
                    <!-- .card-header -->
                    <div class="card-header">
                        <h3 class="card-title">検索条件</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('admin.order.index') }}" method="GET">
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="control-group" id="orderName">
                                    <label class="col-sm-2 control-label">発注名</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="name" class="form-control" size="10" maxlength="100" value="{{ $name }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-secondary">検索</button>
                        </div>

                    </form>
                </div>

                <form action="{{ route('admin.order.index') }}" method="GET" id="pagingForm">
                    <input type="hidden" name="name" value="{{ $name }}">
                </form>

                <div class="row">
                    <div class="col-12">

                        <div class="card card-purple">
                            <!-- .card-header -->
                            <div class="card-header">
                                <h3 class="card-title">検索結果</h3>
                                <div class="dropdown text-right">
                                    <button class="btn btn-default dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        操作
                                        <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                        <a class="dropdown-item text-muted js-download" href="{{ route('admin.order.downloadCsv') }}">CSVダウンロード</a>
                                        <a class="dropdown-item text-muted js-download" href="{{ route('admin.order.downloadPdf') }}">PDFダウンロード</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>注文者</th>
                                            <th>商品名</th>
                                            <th>個数</th>
                                            <th>発注日時</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <th>{{ $order->id }}</th>
                                            <td>{{ $order->user_name }}</td>
                                            <td>{{ $order->stock_name }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>{{ $order->created_at }}</td>
                                            <td><a href="{{ route('admin.order.show', ['id'=> $order->id]) }}">詳細</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <!-- .card-footer -->
                            <div class="card-footer clearfix ">
                                {{ $orders->links() }}
                            </div>
                            <!-- /.card-footer -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- /.content -->

@endsection
