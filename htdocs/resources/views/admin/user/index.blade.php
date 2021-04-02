@extends('layouts.app_admin')

@section('title', '顧客一覧')
@php
$menu = 'user';
$subMenu = 'user';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => '顧客一覧',
  'breadcrumbs' => (object) [
    (object) [
      'label'   => '顧客一覧'
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
                    <form action="{{ route('admin.user.index') }}" method="GET">
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-2 control-label">名前</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="name" class="form-control" size="10" maxlength="100" value="{{ $name }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">メールアドレス</label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control" maxlength="100" value="{{ $email }}">
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-secondary">検索</button>
                        </div>

                    </form>
                </div>

                <form action="{{ route('admin.user.index') }}" method="GET" id="pagingForm">
                    <input type="hidden" name="name" value="{{ $name }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                </form>

                <div class="row">
                    <div class="col-12">

                        <div class="card card-purple">
                            <!-- .card-header -->
                            <div class="card-header">
                                <h3 class="card-title">検索結果</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>氏名</th>
                                            <th>メールアドレス</th>
                                            <th>登録日時</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <th>{{ $user->id }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td><a href="{{ route('admin.user.show', ['id'=> $user->id]) }}">詳細</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <!-- .card-footer -->
                            <div class="card-footer clearfix ">
                                {{ $users->links() }}
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

<script src="{{ asset('/assets/admin/js/user/index.js') }}" defer></script>

@endsection
