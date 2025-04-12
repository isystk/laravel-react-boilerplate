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
            <div class="row">
                <div class="col">
                    <div class="dropdown text-right">
                        <button
                            class="btn btn-primary dropdown-toggle btn-sm"
                            type="button"
                            id="dropdownMenu1"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="true"
                        >
                            {{ __('common.Export') }}
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                            <a
                                class="dropdown-item text-muted js-download"
                                href="{{ route('admin.staff.import.export') . '?file_type=csv' }}"
                            >{{ __('common.CSV Download') }}</a>
                            <a
                                class="dropdown-item text-muted js-download"
                                href="{{ route('admin.staff.import.export') . '?file_type=xlsx' }}"
                            >{{ __('common.Excel Download') }}</a>
                        </div>
                    </div>
                    <div class="text-center" >
                        <form
                            method="POST"
                            action="{{ route('admin.staff.import.store') }}"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="form-group">
                                <input
                                    type="file"
                                    name="upload_file"
                                    @cannot('high-manager')
                                        disabled="disabled"
                                    @endcan
                                >
                            </div>
                            <button
                                class="btn btn-primary"
                                @cannot('high-manager')
                                    disabled="disabled"
                                @endcan
                            >{{ __('common.Execute') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                @if(0 < count($importHistories))
                    <div class="history">
                        <table class="border table">
                           @foreach (array_slice($importHistories, 0, 10) as $importHistory)
                                <tr data-id="{{ $importHistory["id"] }}">
                                    <td>{{ $importHistory["import_at"] }}</td>
                                    <td>{{ $importHistory["import_user_name"] }}</td>
                                    <td>
                                        <a
                                            href="{{ route('admin.staff.import.import_file', $importHistory["id"]) }}"
                                        >{{ $importHistory["file_name"] }}</a>
                                    </td>
                                    <td>{{ $importHistory["status"] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
                </div>
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
