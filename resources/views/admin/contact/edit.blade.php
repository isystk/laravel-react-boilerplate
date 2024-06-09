@extends('layouts.app_admin')

@section('title',  __('order.Order ID:') . $contactForm->id. __('common.Of Change'))
@php
    $menu = 'user';
    $subMenu = 'contact';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.edit', $contactForm) }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.contact.show', ['contactForm' => $contactForm]) }}">{{ __('common.Back') }}</a>
    </div>

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

            <form
                method="POST" enctype="multipart/form-data"
                action="{{ route('admin.contact.update', ['contactForm' => $contactForm]) }}"
            >
                @method('PUT')
                @csrf
                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Name') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="text"
                                name="user_name"
                                value="{{ old('user_name', $contactForm->user_name) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.contact_forms.user_name') }}"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.EMail') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $contactForm->email) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.contact_forms.email') }}"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Gender') }}</label>
                        <div class="col-sm-12">
                            @foreach (App\Enums\Gender::cases() as $e)
                                <label>
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="{{$e->value}}"
                                        class="form-control"
                                        {{ $e->value == old("gender", $contactForm->gender) ? 'checked="checked"' : '' }}
                                    />
                                    <span>{{ $e->label() }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Age') }}</label>
                        <div class="col-sm-12">
                            <select
                                name="age"
                                class="form-control"
                            >
                                <option value="">{{ __('common.Please Select') }}</option>
                                @foreach (App\Enums\Age::cases() as $e)
                                    <option
                                        value="{{$e->value}}"
                                        {{ $e->value == old("age", $contactForm->age) ? 'selected="selected"' : '' }}
                                    >{{$e->label()}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Title') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $contactForm->title) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.contact_forms.title') }}"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Contact') }}</label>
                        <div class="col-sm-12">
                            <textarea
                                name="contact"
                                rows="10"
                                cols="50"
                                class="form-control"
                            >{{ old('contact', $contactForm->contact) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.URL') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="url"
                                name="url"
                                value="{{ old('url', $contactForm->url) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.contact_forms.url') }}"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Image') }}1</label>
                        <div class="col-sm-12">
                            <p><input class="js-uploadImage" type="file" accept="image/png, image/jpeg" >
                            </p>
                            <div class="result">
                                @if (old('imageBase64_1'))
                                    <img src="{{ old('imageBase64_1') }}" width="200px" />
                                    <input type="hidden" name="imageBase64_1" value="{{ old('imageBase64_1') }}" />
                                    <input type="hidden" name="fileName_1" value="{{ old('fileName_1') }}" />
                                @elseif (1 <= $contactFormImages->count())
                                    <img
                                        src="{{ asset('uploads/contact/' . $contactFormImages->first()['file_name']) }}"
                                        width="200px"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Image') }}2</label>
                        <div class="col-sm-12">
                            <p><input class="js-uploadImage" type="file" accept="image/png, image/jpeg" >
                            </p>
                            <div class="result">
                                @if (old('imageBase64_2'))
                                    <img src="{{ old('imageBase64_2') }}" width="200px" />
                                    <input type="hidden" name="imageBase64_2" value="{{ old('imageBase64_2') }}" />
                                    <input type="hidden" name="fileName_2" value="{{ old('fileName_2') }}" />
                                @elseif (2 <= $contactFormImages->count())
                                    <img
                                        src="{{ asset('uploads/contact/' . $contactFormImages->skip(1)->first()['file_name']) }}"
                                        width="200px"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('contact.Image') }}3</label>
                        <div class="col-sm-12">
                            <p><input class="js-uploadImage" type="file" accept="image/png, image/jpeg" >
                            </p>
                            <div class="result">
                                @if (old('imageBase64_3'))
                                    <img src="{{ old('imageBase64_3') }}" width="200px" />
                                    <input type="hidden" name="imageBase64_3" value="{{ old('imageBase64_3') }}" />
                                    <input type="hidden" name="fileName_3" value="{{ old('fileName_3') }}" />
                                @elseif (3 <= $contactFormImages->count())
                                    <img
                                        src="{{ asset('uploads/contact/' . $contactFormImages->skip(2)->first()['file_name']) }}"
                                        width="200px"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <input class="btn btn-info" type="submit" value="{{ __('common.Execute') }}">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            // 画像ファイルアップロード
            $('.js-uploadImage').each(function (i) {
                const self = $(this),
                    parent = self.closest('div'),
                    result = parent.find('.result');
                self.imageUploader({
                    dropAreaSelector: '#drop-zone',
                    successCallback: function (res) {
                        result.empty()
                            .append('<img src="' + res.fileData + '" width="200px" />')
                            .append(`<input type="hidden" name="imageBase64_${i+1}" value="` + res.fileData + '" />')
                            .append('<input type="hidden" name="fileName_${i+1}" value="' + res.fileName + '" />');

                        $('.error-message').empty();
                    },
                    errorCallback: function (res) {
                        $('.error-message').text(res[0]);
                    }
                });
            });
        });
    </script>
@endsection
