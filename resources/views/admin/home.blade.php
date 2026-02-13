@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.admin')
@section('title', __('common.HOME'))

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    @php
                        $today = Carbon::now()->format('Y/m/d');
                    @endphp
                    <a href="{{ route('admin.order') . "?order_date_from={$today}&order_date_to={$today}" }}">
                        <div class="info-box bg-primary text-white">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="secondary-box-text">本日の注文</span>
                                <span class="info-box-number">{{ $todaysOrdersCount }} 件</span>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">売上推移</h3>
                        </div>
                        <div class="card-body">
                            <div id="sales-chart"
                                 data-sales='{!! json_encode($salesByMonth) !!}'
                                 style="width:100%; height:400px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">最新の注文</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>注文ID</th>
                                        <th>注文者</th>
                                        <th>金額</th>
                                        <th>注文日時</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestOrders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? '不明' }}</td>
                                            <td>¥{{ number_format($order->sum_price) }}</td>
                                            <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('admin.order') }}">すべての注文を表示</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/home/index.js')
@endsection
