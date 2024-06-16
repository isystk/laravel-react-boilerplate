@extends('layouts.app_admin')

@section('title',  __('staff.Staff Import'))
@php
    $menu = 'system';
    $subMenu = 'staff';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff.import') }}
@endsection

@section('content')
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.staff') }}">{{ __('common.Back') }}</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-purple">
        <div class="card-body">
            <div class="text-center" >
                <form
                    method="POST"
                    action="{{ route('admin.staff.import.regist') }}"
                >
                    @csrf
                    <button
                        class="btn btn-primary"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >{{ __('common.Import') }}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {

        });
    </script>
@endsection
