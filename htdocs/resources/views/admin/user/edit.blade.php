@extends('layouts.app_admin')

@section('title', '顧客変更')
@php
$menu = 'user';
$subMenu = 'user';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => '顧客変更',
  'breadcrumbs' => (object) [
    (object) [
      'link'   => url('/admin/user'),
      'label'   => '顧客一覧'
    ],
    (object) [
      'label'   => '顧客変更'
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
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" enctype="multipart/form-data" action="{{route('admin.user.update', ['id' => $user->id])}}">
                            @csrf
                            氏名
                            <input type="text" name="name" value="{{ old('name', $user -> name) }}" />
                            <br>
                            メールアドレス
                            <input type="email" name="email" value="{{ old('email', $user -> email) }}" />
                            <br>

                            <input class="btn btn-info" type="submit" value="登録する">
                        </form>

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
