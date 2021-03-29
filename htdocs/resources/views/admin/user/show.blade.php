@extends('layouts.app_admin')

@section('title', '顧客詳細')
@php
$menu = 'user';
$subMenu = 'user';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => '顧客詳細',
  'breadcrumbs' => (object) [
    (object) [
      'link'   => url('/admin/user'),
      'label'   => '顧客一覧'
    ],
    (object) [
      'label'   => '顧客詳細'
    ]
  ]
])


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
                                <label class="col-sm-2 control-label">名前</label>
                                <div class="col-sm-4">
                                    {{ $user -> name }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-2 control-label">メールアドレス</label>
                                <div class="col-sm-4">
                                    {{ $user -> email }}
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer clearfix ">
                        <form method="GET" action="{{route('admin.user.edit', ['id' => $user->id ])}}">
                            @csrf

                            <input class="btn btn-info" type="submit" value="変更する">
                        </form>

                        <form method="POST" action="{{route('admin.user.destroy', ['id' => $user->id ])}}" id="delete_{{ $user->id }}">
                            @csrf
                            <a href="#" class="btn btn-danger" data-id="{{ $user->id }}" onclick="deletePost(this);">削除する</a>
                        </form>
                    </div>
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
