@extends('layouts.app_admin')

@section('title', $stock->name)
@php
$menu = 'master';
$subMenu = 'stock';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.stock.show', $stock) }}
@endsection

@section('content')

<div class="card card-purple">
    <div class="card-body">
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('stock.Name')}}</label>
                <div class="col-sm-4">
                    {{ $stock -> name }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('stock.Detail')}}</label>
                <div class="col-sm-4">
                    {{ $stock -> detail }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('stock.Price')}}</label>
                <div class="col-sm-4">
                    {{ $stock -> price }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('stock.Quantity')}}</label>
                <div class="col-sm-4">
                    {{ $stock -> quantity }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-2 control-label">{{__('stock.Image')}}</label>
                <div class="col-sm-4" id="result">
                    <img src="{{ asset('/uploads/stock/'.$stock->imgpath) }}" alt="" width="200px" id="stockImage">
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-center clearfix ">
        <form method="GET" action="{{route('admin.stock.edit', ['id' => $stock->id ])}}">
            @csrf
            <input class="btn btn-info" type="submit" value="{{__('common.Change')}}">
        </form>
        <form method="POST" action="{{route('admin.stock.destroy', ['id' => $stock->id ])}}" id="delete_{{ $stock->id }}">
            @csrf
            <a href="#" class="btn btn-danger js-deleteBtn" data-id="{{ $stock->id }}" >{{__('common.Delete')}}</a>
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
