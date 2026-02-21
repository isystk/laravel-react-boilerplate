@extends('layouts.admin')
@section('title', 'パスワード変更')

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.passwordChange') }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.home') }}">前に戻る</a>
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

    <form method="POST"
          action="{{ route('admin.passwordChange.update') }}">
        @method('PUT')
        @csrf
        <div class="card card-purple">
            <div class="card-body">
                <div class="form-group">
                    <label for="password"
                           class="form-label fw-bold">パスワード</label>
                    <input type="password"
                           name="password"
                           id="password"
                           value=""
                           class="form-control"
                           maxlength="{{ config('const.maxlength.staffs.password') }}" />
                </div>
                <div class="form-group">
                    <label for="password_confirmation"
                           class="form-label fw-bold">パスワード (確認)</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           value=""
                           class="form-control"
                           maxlength="{{ config('const.maxlength.staffs.password') }}" />
                </div>
                <div class="card-footer text-center  ">
                    <button class="btn btn-primary"
                            type="submit">
                        変更する
                    </button>
                </div>
            </div>
    </form>

@endsection
