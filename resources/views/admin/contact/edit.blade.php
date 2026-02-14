@extends('layouts.admin')
@section('title', __('order.Order ID:') . $contactForm->id . __('common.Of Change'))
@section('mainMenu', 'user')
@section('subMenu', 'contact')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.edit', $contactForm) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.contact.show', ['contactForm' => $contactForm]) }}">{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success"
                     role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('admin.contact.update', ['contactForm' => $contactForm]) }}">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="user_name"
                           class="form-label">{{ __('contact.Name') }}</label>
                    <input type="text"
                           name="user_name"
                           id="user_name"
                           value="{{ old('user_name', $contactForm->user_name) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contact_forms.user_name') }}" />
                </div>

                <div class="form-group">
                    <label for="email"
                           class="form-label">{{ __('contact.EMail') }}</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $contactForm->email) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contact_forms.email') }}" />
                </div>
                <div class="form-group">
                    <label class="form-label d-block">{{ __('contact.Gender') }}</label>
                    <div class="ps-2">
                        @foreach (App\Enums\Gender::cases() as $e)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                       type="radio"
                                       name="gender"
                                       id="gender_{{ $e->value }}"
                                       value="{{ $e->value }}"
                                       {{ $e->value == old('gender', $contactForm->gender->value) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                       for="gender_{{ $e->value }}">
                                    {{ $e->label() }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="age"
                           class="form-label">{{ __('contact.Age') }}</label>
                    <select name="age"
                            id="age"
                            class="form-select">
                        <option value="">{{ __('common.Please Select') }}</option>
                        @foreach (App\Enums\Age::cases() as $e)
                            <option value="{{ $e->value }}"
                                    {{ $e->value == old('age', $contactForm->age->value) ? 'selected' : '' }}>
                                {{ $e->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title"
                           class="form-label">{{ __('contact.Title') }}</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $contactForm->title) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contact_forms.title') }}" />
                </div>

                <div class="form-group">
                    <label for="contact"
                           class="form-label">{{ __('contact.Contact') }}</label>
                    <textarea name="contact"
                              id="contact"
                              rows="10"
                              class="form-control">{{ old('contact', $contactForm->contact) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="url"
                           class="form-label">{{ __('contact.URL') }}</label>
                    <input type="url"
                           name="url"
                           id="url"
                           value="{{ old('url', $contactForm->url) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contact_forms.url') }}" />
                </div>

                <label class="col-sm-2 control-label">{{ __('contact.Image') }}</label>
                <div class="col-sm-10">
                    @include('admin.parts.image_upload', [
                        'id' => 'image',
                        'fileName' => old('image_file_name', $contactForm->image?->file_name),
                        'imageUrl' => $contactForm->image?->getImageUrl(),
                    ])
                </div>

                <div class="card-footer text-center">
                    <input class="btn btn-primary"
                           type="submit"
                           value="{{ __('common.Execute') }}">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/contact/edit.js')
@endsection
