@extends('layouts.admin')
@section('title', '管理者ID:' . $staff->id)
@section('mainMenu', 'system')
@section('subMenu', 'staff')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff.show', $staff) }}
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
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">氏名</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $staff->name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">メールアドレス</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $staff->email }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">権限</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $staff->role->label() }}
                </div>
            </div>
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a class="btn btn-primary"
                       href="{{ route('admin.staff.edit', ['staff' => $staff]) }}"
                       @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                        変更する
                    </a>
                </div>
            </div>
            <div class="d-inline-block position-absolute"
                 style="right: 30px;">
                <form method="POST"
                      action="{{ route('admin.staff.destroy', ['staff' => $staff]) }}"
                      id="delete_{{ $staff->id }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger js-deleteBtn"
                            data-id="{{ $staff->id }}"
                            @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>削除する</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/staff/show.js')
@endsection
