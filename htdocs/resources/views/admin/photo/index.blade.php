@extends('layouts.app_admin')

@section('title', '画像一覧')
@php
$menu = 'system';
$subMenu = 'photo';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => '画像一覧',
  'breadcrumbs' => (object) [
    (object) [
      'label'   => '画像一覧'
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
                    <form action="{{ route('admin.photo.index') }}" method="GET">
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="control-group" id="photoName">
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

                <form action="{{ route('admin.photo.index') }}" method="GET" id="pagingForm">
                    <input type="hidden" name="name" value="{{ $name }}">
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
                                            <th>種別</th>
                                            <th>ファイル名</th>
                                            <th>画像</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($photos as $photo)
                                        <tr>
                                            <td>{{ ($photo->type === 'stock') ? '商品' : 'その他' }}</td>
                                            <td>{{ $photo->fileName }}</td>
                                            <td>
                                              @if ($photo->type === 'stock')
                                                <img src="{{ asset('/uploads/stock/'.$photo->fileName) }}" alt="" width="100px" >
                                              @else
                                                <img src="{{ asset('/uploads/'.$photo->fileName) }}" alt="" width="100px" >
                                              @endif
                                            </td>
                                            <td>
                                              <a href="#" class="btn btn-danger js-delete" data-id="{{$photo->type}}_{{$photo->fileName}}" >削除する</a>
                                              <form id="delete_{{$photo->type}}_{{$photo->fileName}}" action="{{ route('admin.photo.destroy', ['id' => $photo->type.'_'.$photo->fileName]) }}" method="POST" style="display: none;">
                                                  @csrf
                                                  <input type="hidden" name="type" value="{{$photo->type}}" />
                                                  <input type="hidden" name="fileName" value="{{$photo->fileName}}" />
                                              </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <!-- .card-footer -->
                            <div class="card-footer clearfix ">
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

<script src="{{ asset('/assets/admin/js/photo/index.js') }}" defer></script>

@endsection
