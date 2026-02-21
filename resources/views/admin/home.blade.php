@extends('layouts.admin')
@section('title', 'ダッシュボード')

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="{{ route('admin.contact') . '?only_unreplied=1' }}">
                        <div class="info-box bg-primary text-white">
                            <span class="info-box-icon"><i class="fas fa-envelope-open-text"></i></span>
                            <div class="info-box-content">
                                <span class="secondary-box-text">未返信のお問い合わせ</span>
                                <span class="info-box-number">{{ $unrepliedContactsCount }} 件</span>
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
                                 data-sales='{{ json_encode($salesByMonth, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}'
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">月別の新規ユーザー推移</h3>
                        </div>
                        <div class="card-body">
                            <div id="users-chart"
                                 data-users='{{ json_encode($usersByMonth, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}'
                                 style="width:100%; height:400px;"></div>
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
