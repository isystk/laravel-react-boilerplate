@extends('layouts.admin')
@section('title', __('contact.Contact ID:') . $contactForm->id)
@section('mainMenu', 'user')
@section('subMenu', 'contact')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.show', $contactForm) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.contact') }}">{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.Name') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contactForm->user_name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.EMail') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contactForm->email }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.Gender') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contactForm->gender->label() }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.Age') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contactForm->age->label() }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.Title') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contactForm->title }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.Contact') }}</label>
                <div class="col-sm-10 d-flex align-items-center"
                     style="white-space: pre-wrap;">{{ $contactForm->contact }}</div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.URL') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    @if ($contactForm->url)
                        <a href="{{ $contactForm->url }}"
                           target="_blank">{{ $contactForm->url }}</a>
                    @else
                        -
                    @endif
                </div>
            </div>

            @if ($contactForm->image)
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label text-muted small">{{ __('contact.Image') }}</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <img src="{{ $contactForm->image->getImageUrl() }}"
                             class="img-thumbnail"
                             style="max-width: 200px;" />
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a class="btn btn-primary"
                       href="{{ route('admin.contact.edit', ['contactForm' => $contactForm]) }}"
                       @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                        {{ __('common.Change') }}
                    </a>
                </div>
            </div>
            <div class="d-inline-block position-absolute"
                 style="right: 30px;">
                <form method="POST"
                      action="{{ route('admin.contact.destroy', ['contactForm' => $contactForm]) }}"
                      id="delete_{{ $contactForm->id }}">
                    @method('DELETE')
                    @csrf
                    <button href="#"
                            class="btn btn-danger js-deleteBtn"
                            data-id="{{ $contactForm->id }}"
                            @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                        {{ __('common.Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/contact/show.js')
@endsection
