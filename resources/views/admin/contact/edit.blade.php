@extends('layouts.admin')
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
        <a
            class="btn btn-secondary"
            href="{{ route('admin.contact.show', ['contactForm' => $contactForm]) }}"
        >{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            @if (session('status'))
                <div
                    class="alert alert-success"
                    role="alert"
                >
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
                method="POST"
                enctype="multipart/form-data"
                action="{{ route('admin.contact.update', ['contactForm' => $contactForm]) }}"
            >
                @method('PUT')
                @csrf
                <div class="form-group">
                    <div
                        class="control-group"
                        id="userName"
                    >
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
                    <div
                        class="control-group"
                        id="userName"
                    >
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
                    <div
                        class="control-group"
                        id="userName"
                    >
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
                    <div
                        class="control-group"
                        id="userName"
                    >
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
                    <div
                        class="control-group"
                        id="userName"
                    >
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
                    <div
                        class="control-group"
                        id="userName"
                    >
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
                    <div
                        class="control-group"
                        id="userName"
                    >
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
                @for ($i = 1; $i <= 3; $i++)
                    <div class="form-group">
                        <div
                            class="control-group"
                            id="userName"
                        >
                            <label class="col-sm-6 control-label">{{ __('contact.Image') }}{{ $i }}</label>
                            <div class="col-sm-12">
                                <p>
                                    <input
                                        class="js-uploadImage"
                                        type="file"
                                        accept="image/png, image/jpeg"
                                    >
                                </p>
                                <div class="result">
                                    @php
                                        $imageBase64 = old("imageBase64_$i");
                                        $fileName = old("fileName_$i");
                                        $imageExists = $contactFormImages->count() >= $i;
                                        $imageData = $imageExists ? $contactFormImages->skip($i - 1)->first() : null;
                                    @endphp

                                    @if ($imageBase64)
                                        <img
                                            src="{{ $imageBase64 }}"
                                            width="200px"
                                        />
                                        <input
                                            type="hidden"
                                            name="imageBase64_{{ $i }}"
                                            value="{{ $imageBase64 }}"
                                        />
                                        <input
                                            type="hidden"
                                            name="fileName_{{ $i }}"
                                            value="{{ $fileName }}"
                                        />
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm js-remove-image"
                                            data-target="{{ $i }}"
                                        >
                                            {{ __('common.Delete') }}
                                        </button>
                                    @elseif ($imageExists)
                                        <img
                                            src="{{ asset('uploads/contact/' . $imageData['file_name']) }}"
                                            width="200px"
                                        />
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm ml-2 js-remove-image"
                                            data-target="{{ $i }}"
                                        >
                                            {{ __('common.Delete') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
                <div class="card-footer text-center">
                    <input
                        class="btn btn-info"
                        type="submit"
                        value="{{ __('common.Execute') }}"
                    >
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/contact/edit.js')
@endsection
