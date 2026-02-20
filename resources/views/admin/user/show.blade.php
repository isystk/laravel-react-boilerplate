@extends('layouts.admin')
@section('title', $user->name)
@section('mainMenu', 'user')
@section('subMenu', 'user')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user.show', $user) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.user') }}">前に戻る</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">氏名</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $user->name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">メールアドレス</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $user->email }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">アバター</label>
                <div class="col-sm-10 d-flex align-items-center">
                    @if ($user->avatarImage)
                        <img src="{{ $user->avatarImage->getImageUrl() }}"
                             alt="avatar"
                             style="width:64px;height:64px;object-fit:cover;border-radius:50%;" />
                    @elseif ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}"
                             alt="avatar"
                             style="width:64px;height:64px;object-fit:cover;border-radius:50%;" />
                    @else
                        <span class="text-muted">未設定</span>
                    @endif
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">Googleログイン</label>
                <div class="col-sm-10 d-flex align-items-center">
                    @if ($user->google_id)
                        <span class="badge bg-success">はい</span>
                    @else
                        <span class="badge bg-secondary">いいえ</span>
                    @endif
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">ステータス</label>
                <div class="col-sm-10 d-flex align-items-center">
                    @if ($user->status === \App\Enums\UserStatus::Active)
                        <span class="badge bg-success">{{ $user->status->label() }}</span>
                    @else
                        <span class="badge bg-danger">{{ $user->status->label() }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a class="btn btn-primary"
                       href="{{ route('admin.user.edit', ['user' => $user]) }}"
                       {{ !Auth::user()->role->isHighManager() ? 'disabled="disabled"' : '' }}>
                        変更する
                    </a>
                </div>
            </div>
            <div class="d-inline-block position-absolute"
                 style="right: 30px;">
                @if ($user->status->isActive())
                    <form method="POST"
                          action="{{ route('admin.user.suspend', ['user' => $user]) }}"
                          id="suspend_{{ $user->id }}">
                        @method('PUT')
                        @csrf
                        <button class="btn btn-danger js-suspendBtn"
                                data-id="{{ $user->id }}"
                                {{ !Auth::user()->role->isHighManager() ? 'disabled="disabled"' : '' }}>
                            アカウント停止
                        </button>
                    </form>
                @else
                    <form method="POST"
                          action="{{ route('admin.user.activate', ['user' => $user]) }}"
                          id="activate_{{ $user->id }}">
                        @method('PUT')
                        @csrf
                        <button class="btn btn-success js-activateBtn"
                                data-id="{{ $user->id }}"
                                {{ !Auth::user()->role->isHighManager() ? 'disabled="disabled"' : '' }}>
                            有効にする
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/user/show.js')
@endsection
