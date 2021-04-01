@extends('layouts.app_admin')

@section('title', 'お問い合わせ詳細')
@php
$menu = 'user';
$subMenu = 'contact';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => 'お問い合わせ詳細',
  'breadcrumbs' => (object) [
    (object) [
      'link'   => url('/admin/contact'),
      'label'   => 'お問い合わせ一覧'
    ],
    (object) [
      'label'   => 'お問い合わせ詳細'
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
                                    {{ App\Enums\Gender::getDescription($contact -> gender)}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-group">
                                <label class="col-sm-6 control-label">年齢</label>
                                <div class="col-sm-12">
                                    {{ App\Enums\Age::getDescription($contact -> age)}}
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
