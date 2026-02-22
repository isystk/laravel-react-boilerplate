@extends('layouts.admin')
@section('title', '注文ID:' . $order->id)
@section('mainMenu', 'master')
@section('subMenu', 'order')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.order.show', $order) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.order') }}">前に戻る</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">注文者</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $order->user->name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">合計金額</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ number_format($order->sum_price) }}円
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">注文日時</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $order->created_at }}
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>商品名</th>
                            <th class="text-end">合計金額</th>
                            <th class="text-end">個数</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderStocks as $orderStock)
                            <tr>
                                <td>
                                    @if (is_null($orderStock->stock))
                                        削除済み
                                    @else
                                        <a
                                           href={{ route('admin.stock.show', ['stock' => $orderStock->stock]) }}>{{ $orderStock->stock->name }}</a>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($orderStock->price) }}</td>
                                <td class="text-end">{{ number_format($orderStock->quantity) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
