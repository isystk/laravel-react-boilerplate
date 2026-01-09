@extends('layouts.admin')
@section('title', __('order.Order ID:') . $order->id)
@section('mainMenu', 'master')
@section('subMenu', 'order')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.order.show', $order) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.order') }}">{{ __('common.Back') }}</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('order.User Name') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $order->user->name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('order.Sum Price') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ number_format($order->sum_price) }}円
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">{{ __('order.Order Date') }}</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $order->created_at }}
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('order.Stock Name') }}</th>
                            <th class="text-end">{{ __('order.Price') }}</th>
                            <th class="text-end">{{ __('order.Quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderStocks as $orderStock)
                            <tr>
                                <td>{{ $orderStock->stock->name }}</td>
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
