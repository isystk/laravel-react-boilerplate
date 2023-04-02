@extends('layouts.app_admin')

@section('title', __('stock.Stock List'))
@php
$menu = 'master';
$subMenu = 'stock';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.stock') }}
@endsection

@section('content')

<div class="card">
    <div class="card-body text-center">
        <form method="GET" action="{{ route('admin.stock.create') }}">
            <button type="submit" class="btn btn-primary">
                {{__('common.Regist')}}
            </button>
        </form>
    </div>
</div>

<div class="card card-purple">
    <div class="card-header">
        <h3 class="card-title">{{__('common.Search Condition')}}</h3>
    </div>
    <form action="{{ route('admin.stock') }}" method="GET">
        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="form-group">
                <div class="control-group" id="stockName">
                    <label class="col-sm-2 control-label">{{__('stock.Name')}}</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" size="10" maxlength="100" value="{{ $name }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-secondary">{{__('common.Search')}}</button>
        </div>
    </form>
</div>
<form action="{{ route('admin.stock') }}" method="GET" id="pagingForm">
    <input type="hidden" name="name" value="{{ $name }}">
</form>
<div class="row">
    <div class="col-12">
        <div class="card card-purple">
            <div class="card-header">
                <h3 class="card-title">{{__('common.Search Result')}}</h3>
                <div class="dropdown text-right">
                    <button class="btn btn-default dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        {{__('common.Operation')}}
                        <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                        <a class="dropdown-item text-muted js-download" href="{{ route('admin.stock.downloadCsv') }}">{{__('common.CSV Download')}}</a>
                        <a class="dropdown-item text-muted js-download" href="{{ route('admin.stock.downloadExcel') }}">{{__('common.Excel Download')}}</a>
                        <a class="dropdown-item text-muted js-download" href="{{ route('admin.stock.downloadPdf') }}">{{__('common.PDF Download')}}</a>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>{{__('stock.ID')}}</th>
                            <th>{{__('stock.Name')}}</th>
                            <th>{{__('stock.Price')}}</th>
                            <th>{{__('stock.Quantity')}}</th>
                            <th>{{__('common.Registration Date')}}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $stock)
                        <tr>
                            <th>{{ $stock->id }}</th>
                            <td>{{ $stock->name }}</td>
                            <td>{{ $stock->price }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ $stock->created_at }}</td>
                            <td><a href="{{ route('admin.stock.show', ['id'=> $stock->id]) }}">{{__('common.Detail')}}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix ">
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    // ダウンロード
    $('.js-download').click(function(e) {
        e.preventDefault();
        var form = $('#pagingForm');
        form.attr('action', $(this).attr('href'));
        form.submit();
    });
});
</script>
@endsection
