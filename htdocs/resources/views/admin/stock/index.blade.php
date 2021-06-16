@extends('layouts.app_admin')

@section('title', '商品一覧')
@php
$menu = 'master';
$subMenu = 'stock';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => '商品一覧',
  'breadcrumbs' => (object) [
    (object) [
      'label'   => '商品一覧'
    ]
  ]
])

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body text-center">
                        <form method="GET" action="{{ route('admin.stock.create') }}">
                            <button type="submit" class="btn btn-primary">
                                新規登録
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card card-purple">
                    <!-- .card-header -->
                    <div class="card-header">
                        <h3 class="card-title">検索条件</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('admin.stock.index') }}" method="GET">
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="control-group" id="stockName">
                                    <label class="col-sm-2 control-label">名前</label>
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

                <form action="{{ route('admin.stock.index') }}" method="GET" id="pagingForm">
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
                                        <a class="dropdown-item text-muted js-download" href="{{ route('admin.stock.downloadCsv') }}">CSVダウンロード</a>
                                        <a class="dropdown-item text-muted js-download" href="{{ route('admin.stock.downloadPdf') }}">PDFダウンロード</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>商品名</th>
                                            <th>価格</th>
                                            <th>在庫</th>
                                            <th>登録日時</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stocks as $stock)
                                        <tr>
                                            <th>{{ $stock->id }}</th>
                                            <td>{{ $stock->name }}</td>
                                            <td>{{ $stock->price }}</td>
                                            <td>{{ $stock->quantity }}</td>
                                            <td>{{ $stock->created_at }}</td>
                                            <td><a href="{{ route('admin.stock.show', ['id'=> $stock->id]) }}">詳細</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <!-- .card-footer -->
                            <div class="card-footer clearfix ">
                                {{ $stocks->links() }}
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

<script src="{{ asset('/assets/admin/js/stock/index.js') }}" defer></script>

@endsection
