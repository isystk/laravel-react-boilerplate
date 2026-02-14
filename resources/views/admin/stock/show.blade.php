@extends('layouts.admin')
@section('title', $stock->name)
@section('mainMenu', 'master')
@section('subMenu', 'stock')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.stock.show', $stock) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.stock') }}">前に戻る</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">商品名</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $stock->name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">商品説明</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {!! nl2br(e($stock->detail)) !!}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">価格</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ number_format($stock->price) }} 円
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">在庫数</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ number_format($stock->quantity) }} 個
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">商品画像</label>
                <div class="col-sm-10">
                    <div id="result">
                        @if ($stock->image)
                            <img src="{{ $stock->image->getImageUrl() }}"
                                 alt="{{ $stock->name }}"
                                 id="stockImage"
                                 class="img-thumbnail"
                                 style="max-width: 300px; height: auto;" />
                        @else
                            <span class="text-muted small">画像なし</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a class="btn btn-primary"
                       href="{{ route('admin.stock.edit', ['stock' => $stock]) }}"
                       @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                        変更する
                    </a>
                </div>
            </div>
            <div class="d-inline-block position-absolute"
                 style="right: 30px;">
                <form method="POST"
                      action="{{ route('admin.stock.destroy', ['stock' => $stock]) }}"
                      id="delete_{{ $stock->id }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger js-deleteBtn"
                            data-id="{{ $stock->id }}"
                            @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                        削除する
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/stock/show.js')
@endsection
