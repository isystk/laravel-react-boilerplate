@extends('layouts.app_admin')

@section('title', __('common.HOME'))

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.home') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-body">
            <p>{{ __('common.Welcom!') . Auth::user()->name }}</p>
        </div>
    </div>
@endsection

