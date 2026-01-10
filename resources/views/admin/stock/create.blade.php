@extends('layouts.admin')
@section('title', __('stock.Stock Regist'))
@section('mainMenu', 'master')
@section('subMenu', 'stock')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.stock.create') }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.stock') }}">{{ __('common.Back') }}</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success"
             role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card card-purple">
        <div class="card-body">
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('admin.stock.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name"
                           class="col-form-label">{{ __('stock.Name') }}</label>
                    <div class="col-sm-8">
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.stocks.name') }}" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="detail"
                           class="col-form-label">{{ __('stock.Detail') }}</label>
                    <div class="col-sm-8">
                        <textarea name="detail"
                                  id="detail"
                                  rows="10"
                                  class="form-control">{{ old('detail') }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="price"
                           class="col-form-label">{{ __('stock.Price') }}</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="number"
                                   name="price"
                                   id="price"
                                   value="{{ old('price') }}"
                                   class="form-control js-input-number"
                                   maxlength="{{ config('const.maxlength.stocks.price') }}" />
                            <span class="input-group-text">円</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="quantity"
                           class="col-form-label">{{ __('stock.Quantity') }}</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="number"
                                   name="quantity"
                                   id="quantity"
                                   value="{{ old('quantity') }}"
                                   class="form-control js-input-number"
                                   maxlength="{{ config('const.maxlength.stocks.quantity') }}" />
                            <span class="input-group-text">個</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{{ __('stock.Image') }}</label>
                    <div class="col-sm-2"
                         id="drop-zone">
                        <p>
                            <input id="js-uploadImage"
                                   type="file">
                        </p>
                        <div id="result">
                            @if (old('imageBase64'))
                                <img src="{{ old('imageBase64') }}"
                                     width="200px" />
                                <input type="hidden"
                                       name="imageBase64"
                                       value="{{ old('imageBase64') }}" />
                                <input type="hidden"
                                       name="fileName"
                                       value="{{ old('fileName') }}" />
                            @endif
                        </div>
                        <p class="error error-message"></p>
                    </div>
                </div>
                <div class="card-footer text-center  ">
                    <input class="btn btn-primary"
                           type="submit"
                           value="{{ __('common.Execute') }}">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/stock/create.js')
@endsection
