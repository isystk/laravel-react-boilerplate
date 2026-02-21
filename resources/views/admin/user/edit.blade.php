@extends('layouts.admin')
@section('title', 'ユーザID:' . $user->id . 'の変更')
@section('mainMenu', 'user')
@section('subMenu', 'user')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user.edit', $user) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.user.show', ['user' => $user]) }}">前に戻る</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success"
             role="alert">
            {{ session('status') }}
        </div>
    @endif
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
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('admin.user.update', ['user' => $user]) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="name"
                           class="form-label fw-bold">表示名</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $user->name) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.users.name') }}" />
                </div>

                <div class="mb-3">
                    <label for="email"
                           class="form-label fw-bold">メールアドレス</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $user->email) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.users.email') }}" />
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label fw-bold">アバター</label>
                    <div class="col-sm-10">
                        @if ($user->avatarImage)
                            <div class="mb-2">
                                <img src="{{ $user->avatarImage->getImageUrl() }}"
                                     alt="avatar"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:50%;" />
                            </div>
                        @elseif ($user->avatar_url)
                            <div class="mb-2">
                                <img src="{{ $user->avatar_url }}"
                                     alt="avatar"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:50%;" />
                            </div>
                        @endif
                        <input type="file"
                               name="avatar"
                               accept="image/*"
                               class="form-control">
                        <div class="form-text">画像を選択するとアバターが更新されます。 (最大5MB)</div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label fw-bold">Googleログイン</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        @if ($user->google_id)
                            <span class="badge bg-success">はい</span>
                        @else
                            <span class="badge bg-secondary">いいえ</span>
                        @endif
                    </div>
                </div>

                <div class="card-footer text-center  ">
                    <input class="btn btn-primary"
                           type="submit"
                           value="登録する" />
                </div>
            </form>
        </div>
    </div>
@endsection
