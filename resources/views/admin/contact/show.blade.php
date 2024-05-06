@extends('layouts.app_admin')

@section('title', __('contact.Contact ID:') . $contactForm->id)
@php
    $menu = 'user';
    $subMenu = 'contact';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.show', $contactForm) }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.contact') }}">{{ __('common.Back') }}</a>
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
                        {{ $contactForm->getGender()?->label() }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.Age') }}</label>
                    <div class="col-sm-12">
                        {{ $contactForm->getAge()?->label() }}
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
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-6 control-label">{{ __('contact.Image') }}</label>
                    <div class="col-sm-12">
                        @foreach($contactFormImages as $contactFormImage)
                            @if ($contactFormImage['file_name'])
                                <img src="{{ asset('uploads/contact/' . $contactFormImage['file_name']) }}" width="200px" />
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <form
                    method="GET"
                    action="{{ route('admin.contact.edit', ['contactForm' => $contactForm ]) }}"
                >
                    @csrf
                    <div class="mx-auto">
                        <input class="btn btn-info" type="submit" value="{{ __('common.Change') }}">
                    </div>
                </form>
            </div>
            <div class="d-inline-block position-absolute" style="right: 30px;">
                <form
                    method="POST"
                    action="{{ route('admin.contact.destroy', ['contactForm' => $contactForm ]) }}"
                    id="delete_{{ $contactForm->id }}"
                >
                    @csrf
                    <a href="#" class="btn btn-danger js-deleteBtn" data-id="{{ $contactForm->id }}">{{ __('common.Delete') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            // 削除確認用のダイアログを表示
            $('.js-deleteBtn').click(function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('本当に削除していいですか？')) {
                    $('#delete_' + id).submit();
                }
            });
        });
    </script>
@endsection
