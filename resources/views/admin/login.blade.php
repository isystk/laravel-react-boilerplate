@extends('layouts.app_admin')

@section('title', __('common.Login'))

@section('content')
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        {!! RecaptchaV3::field('login') !!}
        <div class="card card-purple">
            <div class="card-body">
                <div class="form-group row">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>
                                <div class="text-danger">
                                    {{ $error }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('common.EMail') }}</label>
                    <div class="col-md-6">
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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        for="password"
                        class="col-md-4 col-form-label text-md-right"
                    >{{ __('common.Password') }}</label>
                    <div class="col-md-6">
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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
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
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-4" style="border: solid 1px #000;">
                        テスト用ユーザ<br/>
                        メールアドレス: sample@sample.com<br>
                        パスワード: password
                    </div>
                </div>
            </div>
            <div class="card-footer text-center  ">
                <button type="submit" class="btn btn-danger" id="Login">
                    {{ __('common.Login') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    {!! RecaptchaV3::initJs() !!}
@endsection
