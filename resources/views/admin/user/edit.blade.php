@extends('layouts.app_admin')

@section('title', $user->name . __('common.Of Change'))
@php
    $menu = 'user';
    $subMenu = 'user';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user.edit', $user) }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.user.show', ['user' => $user]) }}">{{ __('common.Back') }}</a>
    </div>

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

    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.user.update', ['user' => $user]) }}">
        @method('PUT')
        @csrf
        <div class="card card-purple">
            <div class="card-body">
                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('user.Name') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.users.name') }}"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group" id="userName">
                        <label class="col-sm-6 control-label">{{ __('user.EMail') }}</label>
                        <div class="col-sm-12">
                            <input
                                type="text"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control"
                                maxlength="{{ config('const.maxlength.users.email') }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center  ">
                <input class="btn btn-info" type="submit" value="{{__('common.Execute')}}" />
            </div>
        </div>
    </form>

@endsection
