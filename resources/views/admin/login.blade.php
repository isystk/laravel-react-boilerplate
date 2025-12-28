@extends('layouts.admin_simple')
@section('title', __('common.SystemName'))

@section('content')
    <form method="POST" action="{{ route('admin.login') }}" class="p-3">
        @csrf
        {!! RecaptchaV3::field('login') !!}

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('common.EMail') }}</label>
            <input
                id="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                placeholder="メールアドレスを入力して下さい"
            >
            @error('email')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('common.Password') }}</label>
            <input
                id="password"
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                name="password"
                required
                autocomplete="current-password"
                placeholder="パスワードを入力して下さい"
            >
            @error('password')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="remember"
                id="remember" {{ old('remember') ? 'checked' : '' }}
            />
            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>

        <div class="mb-4 p-2 border rounded bg-light small">
            <div>テスト用ユーザ</div>
            <div>メールアドレス: sample@sample.com</div>
            <div>パスワード: password</div>
        </div>

        <div class="d-grid gap-2">
            <button
                type="submit"
                class="btn btn-primary btn-lg"
                id="Login"
            >
                {{ __('common.Login') }}
            </button>
        </div>
    </form>
@endsection

@section('scripts')
    {!! RecaptchaV3::initJs() !!}
@endsection
