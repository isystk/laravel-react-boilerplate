@extends('layouts.app_admin')

@section('title', __('user.User List'))
@php
$menu = 'user';
$subMenu = 'user';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user') }}
@endsection

@section('content')

<div class="card card-purple">
    <div class="card-header">
        <h3 class="card-title">{{__('common.Search Condition')}}</h3>
    </div>
    <form action="{{ route('admin.user') }}" method="GET">
        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="form-group">
                <div class="control-group" id="userName">
                    <label class="col-sm-2 control-label">{{__('user.Name')}}</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" size="10" maxlength="100" value="{{ $name }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">{{__('user.EMail')}}</label>
                <div class="col-sm-8">
                    <input type="email" name="email" class="form-control" maxlength="100" value="{{ $email }}">
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-secondary">{{__('common.Search')}}</button>
        </div>
    </form>
</div>
<form action="{{ route('admin.user') }}" method="GET" id="pagingForm">
    <input type="hidden" name="name" value="{{ $name }}">
    <input type="hidden" name="email" value="{{ $email }}">
</form>
<div class="row">
    <div class="col-12">
        <div class="card card-purple">
            <div class="card-header">
                <h3 class="card-title">{{__('common.Search Result')}}</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>{{__('user.ID')}}</th>
                            <th>{{__('user.Name')}}</th>
                            <th>{{__('user.EMail')}}</th>
                            <th>{{__('common.Registration Date')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <th>{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td><a href="{{ route('admin.user.show', ['id'=> $user->id]) }}">{{__('common.Detail')}}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix ">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
