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
                    <a
                        href="{{ route('admin.order') . "?order_date_from={$today}&order_date_to={$today}" }}"
                    >
                        <div class="info-box bg-primary text-white">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="secondary-box-text">本日の注文</span>
                                <span class="info-box-number">12 件</span>
                            </div>
                        </div>
                    </a>
                </div>



            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">売上推移</h3></div>
                        <div class="card-body">
                            <canvas id="salesChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">最新の注文</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                <tr><th>注文ID</th><th>顧客名</th><th>金額</th></tr>
                                </thead>
                                <tbody>
                                <tr><td>#1024</td><td>山田 太郎</td><td>¥5,400</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('admin.order')}}">すべての注文を表示</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
