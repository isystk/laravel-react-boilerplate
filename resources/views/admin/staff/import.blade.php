@extends('layouts.admin')
@section('title', 'スタッフ一括登録')
@section('mainMenu', 'system')
@section('subMenu', 'staff')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff.import') }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.staff') }}">前に戻る</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
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
                    <div class="dropdown text-end">
                        <button class="btn btn-secondary dropdown-toggle btn-sm"
                                type="button"
                                id="dropdownMenu1"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="true">
                            一括出力
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end"
                             aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item text-muted js-download"
                               href="{{ route('admin.staff.import.export') . '?file_type=csv' }}">CSVダウンロード</a>
                            <a class="dropdown-item text-muted js-download"
                               href="{{ route('admin.staff.import.export') . '?file_type=xlsx' }}">Excelダウンロード</a>
                        </div>
                    </div>
                    <div class="text-center">
                        <form method="POST"
                              action="{{ route('admin.staff.import.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file"
                                       name="upload_file"
                                       @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                            </div>
                            <button class="btn btn-primary"
                                    @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>登録する</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    @if (0 < count($importHistories))
                        <div class="history">
                            <table class="border table">
                                @foreach (array_slice($importHistories, 0, 10) as $importHistory)
                                    <tr data-id="{{ $importHistory['id'] }}">
                                        <td>{{ $importHistory['import_at'] }}</td>
                                        <td>{{ $importHistory['import_user_name'] }}</td>
                                        <td>
                                            <a
                                               href="{{ route('admin.staff.import.import_file', $importHistory['id']) }}">{{ $importHistory['file_name'] }}</a>
                                        </td>
                                        <td>{{ $importHistory['status'] }}</td>
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
