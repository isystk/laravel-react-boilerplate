@extends('layouts.app')

@section('title', 'お知らせ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">お知らせ</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    ログインに成功しました。
                    <p class="mt20"><a href="{{ route('shop.list') }}" class="btn text-danger">トップページへ</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
