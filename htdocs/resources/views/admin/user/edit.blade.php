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

<form method="POST" enctype="multipart/form-data" action="{{route('admin.user.update', ['id' => $user->id])}}">
    @csrf
    <div class="card card-purple">
        <div class="card-body">
            {{__('user.Name')}}
            <input type="text" name="name" value="{{ old('name', $user -> name) }}" />
            <br>
            {{__('user.EMail')}}
            <input type="email" name="email" value="{{ old('email', $user -> email) }}" />
        </div>
        <div class="card-footer text-center clearfix ">
            <input class="btn btn-info" type="submit" value="{{__('common.Execute')}}">
        </div>
    </div>
</form>

@endsection
