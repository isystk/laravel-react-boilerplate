@extends('layouts.admin')
@section('title', __('contact.Contact ID:') . $contactForm->id)
@section('mainMenu', 'user')
@section('subMenu', 'contact')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.show', $contactForm) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a
            class="btn btn-secondary"
            href="{{ route('admin.contact') }}"
        >{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.Name') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->user_name }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.EMail') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->email }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.Gender') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->gender->label() }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.Age') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->age->label() }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.Title') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->title }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.Contact') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->contact }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.URL') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->url }}
                    </div>
                </div>
            </div>
            @foreach($contactFormImages as $i => $contactFormImage)
                @if ($contactFormImage['file_name'])
                    <div class="form-group">
                        <div class="control-group">
                            <label class="col-sm-6 control-label">{{ __('contact.Image') }}{{ $i+1 }}</label>
                            <div class="col-sm-12">
                                <img
                                    src="{{ asset('uploads/contact/' . $contactFormImage['file_name']) }}"
                                    width="200px"
                                />
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a
                        class="btn btn-info"
                        href="{{ route('admin.contact.edit', ['contactForm' => $contactForm ]) }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >
                        {{ __('common.Change') }}
                    </a>
                </div>
            </div>
            <div
                class="d-inline-block position-absolute"
                style="right: 30px;"
            >
                <form
                    method="POST"
                    action="{{ route('admin.contact.destroy', ['contactForm' => $contactForm ]) }}"
                    id="delete_{{ $contactForm->id }}"
                >
                    @method('DELETE')
                    @csrf
                    <button
                        href="#"
                        class="btn btn-danger js-deleteBtn"
                        data-id="{{ $contactForm->id }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >
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
