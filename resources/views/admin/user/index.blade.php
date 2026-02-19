@php use App\Enums\UserStatus; @endphp
@extends('layouts.admin')
@section('title', '顧客一覧')
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
                    <label for="search_user_name"
                           class="col-sm-2 col-form-label">氏名</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="name"
                               id="search_user_name"
                               value="{{ request()->name }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.users.name') }}" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="search_user_email"
                           class="col-sm-2 col-form-label">メールアドレス</label>
                    <div class="col-sm-8">
                        <input type="email"
                               name="email"
                               id="search_user_email"
                               value="{{ request()->email }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.users.email') }}" />
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
               name="name"
               value="{{ request()->name }}" />
        <input type="hidden"
               name="email"
               value="{{ request()->email }}" />
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
                                    'params' => ['name', '氏名'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['email', 'メールアドレス'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', '登録日時'],
                                ])
                                <th>ステータス</th>
                                <th>アバター</th>
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
                                        @if ($user->avatarImage)
                                            <img src="{{ $user->avatarImage->getImageUrl() }}"
                                                 alt="avatar"
                                                 style="width:40px;height:40px;object-fit:cover;border-radius:50%;" />
                                        @elseif ($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}"
                                                 alt="avatar"
                                                 style="width:40px;height:40px;object-fit:cover;border-radius:50%;" />
                                        @else
                                            <span class="text-muted small">-</span>
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
