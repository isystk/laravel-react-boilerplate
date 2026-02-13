@extends('layouts.admin')
@section('title', __('stock.Stock List'))
@section('mainMenu', 'master')
@section('subMenu', 'stock')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.stock') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body text-center">
            <a href="{{ route('admin.stock.create') }}"
               class="btn btn-primary"
               @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                {{ __('common.Regist') }}
            </a>
        </div>
    </div>

    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">{{ __('common.Search Condition') }}</h3>
        </div>
        <form action="{{ route('admin.stock') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="search_name"
                           class="col-sm-2 col-form-label">{{ __('stock.Name') }}</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="name"
                               id="search_name"
                               value="{{ request()->name }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.stocks.name') }}" />
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit"
                        class="btn btn-secondary">{{ __('common.Search') }}</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.stock') }}"
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
                    <div class="dropdown text-end">
                        <button class="btn btn-secondary dropdown-toggle btn-sm"
                                type="button"
                                id="dropdownMenu1"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="true">
                            {{ __('common.Operation') }}
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end"
                             aria-labelledby="dropdownMenu1">
                            <a class="dropdown-item text-muted js-download"
                               href="{{ route('admin.stock.export') . '?file_type=csv' }}">{{ __('common.CSV Download') }}</a>
                            <a class="dropdown-item text-muted js-download"
                               href="{{ route('admin.stock.export') . '?file_type=xlsx' }}">{{ __('common.Excel Download') }}</a>
                            <a class="dropdown-item text-muted js-download"
                               href="{{ route('admin.stock.export') . '?file_type=pdf' }}">{{ __('common.PDF Download') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['id', __('stock.ID')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['name', __('stock.Name')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['price', __('stock.Price')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['quantity', __('stock.Quantity')],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', __('common.Registration Date')],
                                ])
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $stock)
                                <tr>
                                    <th>{{ $stock->id }}</th>
                                    <td class="mw-500">{{ $stock->name }}</td>
                                    <td class="text-end">{{ $stock->price }}</td>
                                    <td class="text-end">{{ $stock->quantity }}</td>
                                    <td>{{ $stock->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('admin.stock.show', ['stock' => $stock]) }}">{{ __('common.Detail') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $stocks->links('admin.parts.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/stock/index.js')
@endsection
