@extends('layouts.admin_simple')
@section('title', 'LaraEC')

@section('content')
    <form method="POST"
          action="{{ route('admin.login') }}"
          class="p-3">
        @csrf

        <div class="mb-3">
            <label for="email"
                   class="form-label">メールアドレス</label>
            <input id="email"
                   type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="email"
                   autofocus
                   placeholder="メールアドレスを入力して下さい">
            @error('email')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password"
                   class="form-label">パスワード</label>
            <input id="password"
                   type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="パスワードを入力して下さい">
            @error('password')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input class="form-check-input"
                   type="checkbox"
                   name="remember"
                   id="remember"
                   {{ old('remember') ? 'checked' : '' }} />
            <label class="form-check-label"
                   for="remember">
                ログイン状態を保持する
            </label>
        </div>

        <div class="mb-4 p-2 border rounded bg-light small">
            <div>Test User</div>
            <div>Email: admin1@test.com</div>
            <div>Password: password</div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit"
                    class="btn btn-primary btn-lg"
                    id="Login">
                ログイン
            </button>
        </div>
    </form>
@endsection
