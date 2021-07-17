@extends('layouts.app_admin')

@section('title', 'HOME')

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.home') }}
@endsection

@section('content')

<div class="card card-purple">
  <div class="card-body">
    <p>{{ Auth::user()->name }} さん。こんにちわ。</p>
  </div>
</div>

@endsection
