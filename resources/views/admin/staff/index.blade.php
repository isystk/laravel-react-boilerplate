@extends('layouts.admin')
@section('title', 'スタッフ一覧')
@section('mainMenu', 'system')
@section('subMenu', 'staff')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.staff') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body text-center">
            <div class="btn-container d-flex justify-content-between">
                <a href="{{ route('admin.staff.create') }}"
                   class="btn btn-primary m-auto"
                   @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                    新規登録
                </a>
                <a href="{{ route('admin.staff.import') }}"
                   class="btn btn-primary position-absolute"
                   style="right: 20px"
                   @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                    一括登録
                </a>
            </div>
        </div>
    </div>

    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">検索条件</h3>
        </div>
        <form action="{{ route('admin.staff') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="name"
                           class="col-sm-2 col-form-label fw-bold">氏名</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ request()->name }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.admins.name') }}" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="email"
                           class="col-sm-2 col-form-label fw-bold">メールアドレス</label>
                    <div class="col-sm-4">
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ request()->email }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.admins.email') }}" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="role"
                           class="col-sm-2 col-form-label fw-bold">権限</label>
                    <div class="col-sm-4">
                        <select name="role"
                                id="role"
                                class="form-select">
                            <option value="">未選択</option>
                            @foreach (App\Enums\AdminRole::cases() as $item)
                                <option value="{{ $item->value }}"
                                        {{ $item->value == request()->role ? 'selected' : '' }}>{{ $item->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit"
                        class="btn btn-secondary">検索</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.staff') }}"
          method="GET"
          id="pagingForm">
        <input type="hidden"
               name="name"
               value="{{ request()->name }}" />
        <input type="hidden"
               name="email"
               value="{{ request()->email }}" />
        <input type="hidden"
               name="role"
               value="{{ request()->role }}" />
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">検索結果</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['id', 'ID'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['name', '氏名'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['email', 'メールアドレス'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['role', '権限'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', '登録日時'],
                                ])
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffs as $staff)
                                <tr>
                                    <th>{{ $staff->id }}</th>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ $staff->role->label() }}</td>
                                    <td>{{ $staff->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('admin.staff.show', ['staff' => $staff]) }}">詳細</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $staffs->links('admin.parts.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
