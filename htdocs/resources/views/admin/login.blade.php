@extends('layouts.app_admin')

@section('title', __('common.Login'))

@section('content')

<form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div class="card card-purple">
        <div class="card-body">
            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('common.EMail') }}</label>
                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@co.jp">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('common.Password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="adminadmin">
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
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                        <p class="fz-s">email: sample@sample.com<br>
                            password: password</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer text-center clearfix ">
            <button type="submit" class="btn btn-danger">
                {{ __('common.Login') }}
            </button>
        </div>
    </div>
</form>

@endsection
