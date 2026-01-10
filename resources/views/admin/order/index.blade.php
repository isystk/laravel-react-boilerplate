@extends('layouts.admin')
@section('title', __('order.Order List'))
@section('mainMenu', 'master')
@section('subMenu', 'order')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.order') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.order') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="name"
                           class="col-sm-2 col-form-label">{{ __('order.User Name') }}</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ request()->name }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.users.name') }}" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="order_date_from"
                           class="col-sm-2 col-form-label">{{ __('order.Order Date') }}</label>
                    <div class="col-sm-10">
                        <div class="row align-items-center g-2">
                            <div class="col-auto"
                                 style="width: 180px;">
                                <input type="text"
                                       name="order_date_from"
                                       id="order_date_from"
                                       value="{{ request()->order_date_from }}"
                                       class="form-control date-picker"
                                       maxlength="{{ config('const.maxlength.commons.date') }}" />
                            </div>
                            <div class="col-auto text-center">
                                <span>～</span>
                            </div>
                            <div class="col-auto"
                                 style="width: 180px;">
                                <input type="text"
                                       name="order_date_to"
                                       id="order_date_to"
                                       value="{{ request()->order_date_to }}"
                                       class="form-control date-picker"
                                       maxlength="{{ config('const.maxlength.commons.date') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit"
                        class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.order') }}"
          method="GET"
          id="pagingForm">
        <input type="hidden"
               name="name"
               value="{{ request()->name }}">
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">{{ __('common.Search Result') }}</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['id', __('order.ID')],
                                ])
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['users.name', __('order.User Name')],
                                ])
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['sum_price', __('order.Sum Price')],
                                ])
                                @include('admin.common.sortablelink_th', [
                                    'params' => ['created_at', __('order.Order Date')],
                                ])
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <th>{{ $order->id }}</th>
                                    <td>{{ $order->user->name }}</td>
                                    <td class="text-end">{{ $order->sum_price }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('admin.order.show', ['order' => $order]) }}">{{ __('common.Detail') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $orders->links('admin.common.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
