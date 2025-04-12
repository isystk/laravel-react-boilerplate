@extends('layouts.app_admin')

@section('title', __('contact.Contact List'))
@php
    $menu = 'user';
    $subMenu = 'contact';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.contact') }}" method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-2 control-label">{{ __('contact.Name') }}</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                name="userName"
                                value="{{ $request->userName }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.contact_forms.user_name') }}"
                            />
                        </div>
                    </div>
                    <div class="control-group mt-3" id="userName">
                        <label class="col-sm-2 control-label">{{ __('contact.Title') }}</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                name="title"
                                value="{{ $request->title }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.contact_forms.title') }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.contact') }}" method="GET" id="pagingForm">
        <input type="hidden" name="userName" value="{{ $request->userName }}">
        <input type="hidden" name="title" value="{{ $request->title }}">
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.Search Result') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                @include('admin.common.sortablelink_th', ['params' => ['id', __('contact.ID')]])
                                @include('admin.common.sortablelink_th', ['params' => ['user_name', __('contact.Name')]])
                                @include('admin.common.sortablelink_th', ['params' => ['title', __('contact.Title')]])
                                @include('admin.common.sortablelink_th', ['params' => ['created_at', __('common.Registration Date')]])
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($contactForms as $contactForm)
                            <tr>
                                <th>{{ $contactForm->id }}</th>
                                <td>{{ $contactForm->user_name }}</td>
                                <td
                                    class="text-truncate"
                                    style="max-width: 350px;"
                                >
                                    {{ $contactForm->title }}
                                </td>
                                <td>{{ $contactForm->created_at }}</td>
                                <td>
                                    <a
                                        class="btn btn-info btn-sm"
                                        href="{{ route('admin.contact.show', ['contactForm'=> $contactForm]) }}"
                                    >{{ __('common.Detail') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $contactForms->links('admin.common.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
