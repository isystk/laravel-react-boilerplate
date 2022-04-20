@extends('layouts.app_admin')

@section('title',  __('order.Order ID:') . $contact->id. __('common.Of Change'))
@php
$menu = 'user';
$subMenu = 'contact';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.edit', $contact) }}
@endsection

@section('content')

<div class="card card-purple">
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
                    <label class="col-sm-6 control-label">{{__('contact.Name')}}</label>
                    <div class="col-sm-12">
                        <input type="text" name="your_name" value="{{ old('your_name', $contact -> your_name) }}" placeholder="サンプル太郎" maxlength="100">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group" id="userName">
                    <label class="col-sm-6 control-label">{{__('contact.EMail')}}</label>
                    <div class="col-sm-12">
                        <input type="email" name="email" value="{{ old('email', $contact -> email) }}" placeholder="sample@sample.com" maxlength="100">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="control-group" id="userName">
                    <label class="col-sm-6 control-label">{{__('contact.Gender')}}</label>
                    <div class="col-sm-12">
                        @foreach (App\Enums\Gender::asArray() as $gender)
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
                    <label class="col-sm-6 control-label">{{__('contact.Age')}}</label>
                    <div class="col-sm-12">
                        <select name="age">
                            <option value="">{{__('common.Please Select')}}</option>
                            @foreach (App\Enums\Age::asArray() as $age)
                              <option value="{{$age}}" {{ $age == old("age", $contact -> age) ? 'selected="selected"' : '' }}>{{App\Enums\Age::getDescription($age)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="control-group" id="userName">
                    <label class="col-sm-6 control-label">{{__('contact.Title')}}</label>
                    <div class="col-sm-12">
                        <input type="text" name="title" value="{{ old('title', $contact -> title) }}" placeholder="○○について" maxlength="100">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="control-group" id="userName">
                    <label class="col-sm-6 control-label">{{__('contact.Contact')}}</label>
                    <div class="col-sm-12">
                        <textarea name="contact" rows="8" cols="80">{{ old('contact', $contact -> contact) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="control-group" id="userName">
                    <label class="col-sm-6 control-label">{{__('contact.URL')}}</label>
                    <div class="col-sm-12">
                        <input type="url" name="url" value="{{ old('url', $contact -> url) }}" placeholder="https://sample.com" maxlength="100">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="control-group" id="userName">
                    <label class="col-sm-6 control-label">{{__('contact.Image')}}</label>
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
            <div class="card-footer text-center clearfix ">
                <input class="btn btn-info" type="submit" value="{{__('common.Execute')}}">
            </div>
        </form>
    </div>
    @endsection

    @section('scripts')
    <script>
    $(function () {
        // 画像ファイルアップロード
        $('#js-uploadImage').imageUploader({
            dropAreaSelector: '#drop-zone',
            successCallback: function (res) {

                $('#result').empty();
                $('#result').append('<img src="' + res.fileData + '" width="200px" />');
                $('#result').append('<input type="hidden" name="imageBase64" value="' + res.fileData + '" />');
                $('#result').append('<input type="hidden" name="fileName" value="' + res.fileName + '" />');

                $('.error-message').empty();
            },
            errorCallback: function (res) {
                $('.error-message').text(res[0]);
            }
        });
    });
    </script>
    @endsection
