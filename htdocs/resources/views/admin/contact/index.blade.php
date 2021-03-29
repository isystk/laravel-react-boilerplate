@extends('layouts.app_admin')

@section('title', 'お問い合わせ一覧')
@php
$menu = 'user';
$subMenu = 'contact';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => 'お問い合わせ一覧',
  'breadcrumbs' => (object) [
    (object) [
      'label'   => 'お問い合わせ一覧'
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
                    <form action="{{ route('admin.contact.index') }}" method="GET">
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-2 control-label">氏名</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="search" class="form-control" size="10" maxlength="100" value="{{ $search }}">
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

                <form action="{{ route('admin.contact.index') }}" method="GET" id="pagingForm">
                    <input type="hidden" name="search" value="{{ $search }}">
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
                                <table class="table table-hover" >
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>氏名</th>
                                            <th>件名</th>
                                            <th>登録日時</th>
                                            <th>詳細</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($contacts as $contact)
                                        <tr>
                                            <th>{{ $contact->id }}</th>
                                            <td>{{ $contact->your_name }}</td>
                                            <td>@php echo mb_strimwidth($contact->title, 0, 50, '...') @endphp</td>
                                            <td>{{ $contact->created_at }}</td>
                                            <td><a href="{{ route('admin.contact.show', ['id'=> $contact->id]) }}">詳細を見る</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <!-- .card-footer -->
                            <div class="card-footer clearfix ">
                                {{ $contacts->links() }}
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
