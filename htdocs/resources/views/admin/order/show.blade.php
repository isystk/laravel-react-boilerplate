@extends('layouts.app_admin')

@section('title', '注文履歴詳細')
@php
$menu = 'master';
$subMenu = 'order';
@endphp

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>注文履歴詳細</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/order') }}">発注一覧</a></li>
                    <li class="breadcrumb-item active">注文履歴詳細</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

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

<script>
    // 削除確認用のダイアログを表示
    function deletePost(e) {
        'use strict';
        if (confirm('本当に削除していいですか？')) {
            document.getElementById('delete_' + e.dataset.id).submit();
        }
    }
</script>

@endsection
