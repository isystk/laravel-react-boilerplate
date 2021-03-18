@extends('layouts.app_admin')

@section('title', 'お問い合わせ詳細')
@php
$menu = 'contact';
$subMenu = 'contact';
@endphp

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>お問い合わせ詳細</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/contact') }}">お問い合わせ一覧</a></li>
                    <li class="breadcrumb-item active">お問い合わせ詳細</li>
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
                                <label class="col-sm-6 control-label">お名前</label>
                                <div class="col-sm-12">
                                    {{ $contact -> your_name }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">メールアドレス</label>
                                <div class="col-sm-12">
                                    {{ $contact -> email }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">性別</label>
                                <div class="col-sm-12">
                                    {{ "0" == $contact -> gender ? '女性' : '' }}
                                    {{ "1" == $contact -> gender ? '男性' : '' }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">年齢</label>
                                <div class="col-sm-12">
                                    {{ "1" == $contact -> age ? '～19歳' : '' }}
                                    {{ "2" == $contact -> age ? '20歳～29歳' : '' }}
                                    {{ "3" == $contact -> age ? '30歳～39歳' : '' }}
                                    {{ "4" == $contact -> age ? '40歳～49歳' : '' }}
                                    {{ "5" == $contact -> age ? '50歳～59歳' : '' }}
                                    {{ "6" == $contact -> age ? '60歳～' : '' }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">件名</label>
                                <div class="col-sm-12">
                                    {{ $contact -> title }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">お問い合わせ内容</label>
                                <div class="col-sm-12">
                                    {{ $contact -> contact }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">ホームページURL</label>
                                <div class="col-sm-12">
                                    {{ $contact -> url }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">画像</label>
                                <div class="col-sm-12">
                                    @foreach($contact -> contactFormImages as $contactFormImage)
                                    @if ($contactFormImage['file_name'])
                                    <img src="{{ asset('uploads/' . $contactFormImage['file_name']) }}" width="200px">
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer clearfix ">
                        <form method="GET" action="{{route('admin.contact.edit', ['id' => $contact->id ])}}">
                            @csrf

                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-info">変更する</button>
                            </div>

                        </form>

                        <form method="POST" action="{{route('admin.contact.destroy', ['id' => $contact->id ])}}" id="delete_{{ $contact->id }}">
                            @csrf
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-danger" data-id="{{ $contact->id }}" onclick="deletePost(this);">削除する</a>
                            </div>
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
