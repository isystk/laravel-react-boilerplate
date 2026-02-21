@php use App\Enums\UserStatus; @endphp
@extends('layouts.admin')
@section('title', 'ユーザ一覧')
@section('mainMenu', 'user')
@section('subMenu', 'user')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user') }}
@endsection

@section('content')

    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">検索条件</h3>
        </div>
        <form action="{{ route('admin.user') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="keyword"
                           class="col-sm-2 col-form-label fw-bold">ID / 名前 / メールアドレス</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="keyword"
                               id="keyword"
                               value="{{ request()->keyword }}"
                               class="form-control" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="status"
                           class="col-sm-2 col-form-label fw-bold">ステータス</label>
                    <div class="col-sm-4">
                        <select name="status"
                                id="status"
                                class="form-select">
                            <option value="">すべて</option>
                            @foreach (UserStatus::cases() as $userStatus)
                                <option value="{{ $userStatus->value }}"
                                        {{ request()->filled('status') && (int) request()->input('status') === $userStatus->value ? 'selected' : '' }}>
                                    {{ $userStatus->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="has_google"
                           class="col-sm-2 col-form-label fw-bold">Google</label>
                    <div class="col-sm-4">
                        <select name="has_google"
                                id="has_google"
                                class="form-select">
                            <option value="">すべて</option>
                            <option value="1"
                                    {{ request()->input('has_google') === '1' ? 'selected' : '' }}>あり</option>
                            <option value="0"
                                    {{ request()->input('has_google') === '0' ? 'selected' : '' }}>なし</option>
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
    <form action="{{ route('admin.user') }}"
          method="GET"
          id="pagingForm">
        <input type="hidden"
               name="keyword"
               value="{{ request()->keyword }}" />
        <input type="hidden"
               name="status"
               value="{{ request()->input('status') }}" />
        <input type="hidden"
               name="has_google"
               value="{{ request()->input('has_google') }}" />
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">検索結果</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['id', 'ID'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['name', '名前'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['email', 'メールアドレス'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', '登録日時'],
                                ])
                                <th>ステータス</th>
                                <th>Google</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th>{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        @if ($user->status->isActive())
                                            <span class="badge bg-success">{{ UserStatus::Active->label() }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ UserStatus::Suspended->label() }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->google_id)
                                            <span class="badge bg-success">はい</span>
                                        @else
                                            <span class="badge bg-secondary">いいえ</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('admin.user.show', ['user' => $user]) }}">詳細</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $users->links('admin.parts.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
