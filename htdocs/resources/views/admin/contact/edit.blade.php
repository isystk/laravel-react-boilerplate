@extends('layouts.app_admin')

@section('title', 'お問い合わせ変更')
@php
$menu = 'user';
$subMenu = 'contact';
@endphp

@section('content')

@include('admin.common.breadcrumb', [
  'title' => 'お問い合わせ変更',
  'breadcrumbs' => (object) [
    (object) [
      'link'   => url('/admin/contact'),
      'label'   => 'お問い合わせ一覧'
    ],
    (object) [
      'label'   => 'お問い合わせ変更'
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

                        <form method="POST" enctype="multipart/form-data" action="{{route('admin.contact.update', ['id' => $contact->id])}}">
                            @csrf

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">氏名</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="your_name" value="{{ old('your_name', $contact -> your_name) }}" placeholder="サンプル太郎" maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">メールアドレス</label>
                                    <div class="col-sm-12">
                                        <input type="email" name="email" value="{{ old('email', $contact -> email) }}" placeholder="sample@sample.com" maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">性別</label>
                                    <div class="col-sm-12">
                                        @foreach (App\Enums\Gender::toArray() as $gender)
                                          <label>
                                              <input type="radio" name="gender" value="{{$gender}}" {{ $gender == old("gender", $contact -> gender) ? 'checked="checked"' : '' }}>
                                              <span>{{App\Enums\Gender::getDescription($gender)}}</span>
                                          </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">年齢</label>
                                    <div class="col-sm-12">
                                        <select name="age">
                                            <option value="">選択してください</option>
                                            @foreach (App\Enums\Age::toArray() as $age)
                                              <option value="{{$age}}" {{ $age == old("age", $contact -> age) ? 'selected="selected"' : '' }}>{{App\Enums\Age::getDescription($age)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">件名</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="title" value="{{ old('title', $contact -> title) }}" placeholder="○○について" maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">お問い合わせ内容</label>
                                    <div class="col-sm-12">
                                        <textarea name="contact" rows="8" cols="80">{{ old('contact', $contact -> contact) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">ホームページURL</label>
                                    <div class="col-sm-12">
                                        <input type="url" name="url" value="{{ old('url', $contact -> url) }}" placeholder="https://sample.com" maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group" id="userName">
                                    <label class="col-sm-6 control-label">画像</label>
                                    <div class="col-sm-12">
                                        {{--
                                    <p><input type="file" name="imageFile"></p>
                                    <br>
                                    --}}
                                        <p><input id="js-uploadImage" type="file">
                                        </p>
                                        <div id="result">
                                            @if (old('imageBase64'))
                                            <img src="{{ old('imageBase64') }}" width="200px" />
                                            <input type="hidden" name="imageBase64" value="{{ old('imageBase64') }}" />
                                            <input type="hidden" name="fileName" value="{{ old('fileName') }}" />
                                            @elseif ($contact -> contactFormImages)
                                            @foreach($contact -> contactFormImages as $contactFormImage)
                                            @if ($contactFormImage['file_name'])
                                            <img src="{{ asset('uploads/' . $contactFormImage['file_name']) }}" width="200px" id="contactFormImage">
                                            @endif
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-wrap">
                                <input class="submit-btn btn btn-info" type="submit" value="登録する">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/admin/js/contact/edit.js') }}" defer></script>

    @endsection
