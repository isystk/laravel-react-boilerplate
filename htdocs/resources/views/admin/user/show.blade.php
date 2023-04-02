@extends('layouts.app_admin')

@section('title', $user->name)
@php
$menu = 'user';
$subMenu = 'user';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.user.show', $user) }}
@endsection

@section('content')

<div class="card card-purple">
    <div class="card-body">
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('user.Name')}}</label>
                <div class="col-sm-4">
                    {{ $user -> name }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('user.EMail')}}</label>
                <div class="col-sm-4">
                    {{ $user -> email }}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-center clearfix ">
        <form method="GET" action="{{route('admin.user.edit', ['id' => $user->id ])}}">
            @csrf
            <input class="btn btn-info" type="submit" value="{{__('common.Change')}}">
        </form>
        <form method="POST" action="{{route('admin.user.destroy', ['id' => $user->id ])}}" id="delete_{{ $user->id }}">
            @csrf
            <a href="#" class="btn btn-danger js-deleteBtn" data-id="{{ $user->id }}" >{{__('common.Delete')}}</a>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(function() {
    // 削除確認用のダイアログを表示
    $('.js-deleteBtn').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('本当に削除していいですか？')) {
            $('#delete_' + id).submit();
        }
    });
});
</script>
@endsection
