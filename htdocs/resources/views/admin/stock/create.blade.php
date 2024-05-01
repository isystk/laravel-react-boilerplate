@extends('layouts.app_admin')

@section('title', __('stock.Stock Regist'))
@php
    $menu = 'master';
    $subMenu = 'stock';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.stock.create') }}
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.stock.store') }}">
        @csrf
        <div class="card card-purple">
            <div class="card-body">
                <div class="form-group">
                    <div class="control-group">
                        <label class="col-sm-2 control-label">{{ __('stock.Name') }}</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" value="{{ old('name') }}"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group">
                        <label class="col-sm-2 control-label">{{ __('stock.Detail') }}</label>
                        <div class="col-sm-8">
                            <textarea name="detail" rows="8" cols="80">{{ old('detail') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group">
                        <label class="col-sm-2 control-label">{{ __('stock.Price') }}</label>
                        <div class="col-sm-4">
                            <input type="text" name="price" value="{{ old('price') }}"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group">
                        <label class="col-sm-2 control-label">{{ __('stock.Quantity') }}</label>
                        <div class="col-sm-4">
                            <input type="text" name="quantity" value="{{ old('quantity') }}"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-group">
                        <label class="col-sm-2 control-label">{{ __('stock.Image') }}</label>
                        <div class="col-sm-2" id="drop-zone">
                            {{--
                            <p><input type="file" name="imageFile"></p>
                            <br>
                            --}}
                            <p><input id="js-uploadImage" type="file"></p>
                            <div id="result">
                                @if (old('imageBase64'))
                                    <img src="{{ old('imageBase64') }}" width="200px"/>
                                    <input type="hidden" name="imageBase64" value="{{ old('imageBase64') }}" />
                                    <input type="hidden" name="fileName" value="{{ old('fileName') }}" />
                                @endif
                            </div>
                            <p class="error error-message"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center clearfix ">
                <input class="btn btn-info" type="submit" value="{{ __('common.Execute') }}">
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(function () {
            // 画像ファイルアップロード
            $('#js-uploadImage').imageUploader({
                dropAreaSelector: '#drop-zone',
                successCallback: function (res) {

                    $('#result').empty();
                    $('#result').append('<img src="' + res.fileData + '" width="200px" />');
                    $('#result').append('<input type="hidden" name="imageBase64" value="' + res.fileData + '" />');
                    $('#result').append('<input type="hidden" name="fileName" value="' + res.fileName + '" />');

                    $('.error-message').empty();
                },
                errorCallback: function (res) {
                    $('.error-message').text(res[0]);
                }
            });
        });
    </script>
@endsection
