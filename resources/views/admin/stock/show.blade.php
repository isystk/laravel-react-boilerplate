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
    <div class="text-left mb-3">
        <a class="btn btn-secondary" href="{{ route('admin.stock') }}">{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('stock.Name') }}</label>
                    <div class="col-sm-4">
                        {{ $stock -> name }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('stock.Detail') }}</label>
                    <div class="col-sm-4">
                        {{ $stock -> detail }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('stock.Price') }}</label>
                    <div class="col-sm-4">
                        {{ $stock -> price }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('stock.Quantity') }}</label>
                    <div class="col-sm-4">
                        {{ $stock -> quantity }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="control-group">
                    <label class="col-sm-2 control-label">{{ __('stock.Image') }}</label>
                    <div class="col-sm-4" id="result">
                        <img src="{{ asset('/uploads/stock/'.$stock->imgpath) }}" alt="" width="200px" id="stockImage" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a
                        class="btn btn-info"
                        href="{{ route('admin.stock.edit', ['stock' => $stock ]) }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >
                        {{ __('common.Change') }}
                    </a>
                </div>
            </div>
            <div class="d-inline-block position-absolute" style="right: 30px;">
                <form
                    method="POST"
                    action="{{ route('admin.stock.destroy', ['stock' => $stock ]) }}"
                    id="delete_{{ $stock->id }}"
                >
                    @method('DELETE')
                    @csrf
                    <button
                        class="btn btn-danger js-deleteBtn"
                        data-id="{{ $stock->id }}"
                        @cannot('high-manager')
                            disabled="disabled"
                        @endcan
                    >
                        {{ __('common.Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            // 削除確認用のダイアログを表示
            $('.js-deleteBtn').click(function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('本当に削除していいですか？')) {
                    $('#delete_' + id).submit();
                }
            });
        });
    </script>
@endsection
