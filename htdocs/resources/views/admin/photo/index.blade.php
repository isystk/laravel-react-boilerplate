@extends('layouts.app_admin')

@section('title', __('photo.Photo List'))
@php
$menu = 'system';
$subMenu = 'photo';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.photo') }}
@endsection

@section('content')

<div class="card card-purple">
    <div class="card-header">
        <h3 class="card-title">{{__('common.Search Condition')}}</h3>
    </div>
    <form action="{{ route('admin.photo') }}" method="GET">
        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="form-group">
                <div class="control-group" id="photoName">
                    <label class="col-sm-2 control-label">{{__('photo.File Name')}}</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" size="10" maxlength="100" value="{{ $name }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-secondary">{{__('common.Search')}}</button>
        </div>
    </form>
</div>
<form action="{{ route('admin.photo') }}" method="GET" id="pagingForm">
    <input type="hidden" name="name" value="{{ $name }}">
</form>
<div class="row">
    <div class="col-12">
        <div class="card card-purple">
            <div class="card-header">
                <h3 class="card-title">{{__('common.Search Result')}}</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>{{__('photo.Type')}}</th>
                            <th>{{__('photo.File Name')}}</th>
                            <th>{{__('photo.Image')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($photos as $photo)
                        <tr>
                            <td>{{ ($photo->type === 'stock') ? App\Enums\PhotoType::Stock->value : App\Enums\PhotoType::Contact->value }}</td>
                            <td>{{ $photo->fileName }}</td>
                            <td>
                              @if ($photo->type === 'stock')
                                <img src="{{ asset('/uploads/stock/'.$photo->fileName) }}" alt="" width="100px" >
                              @else
                                <img src="{{ asset('/uploads/'.$photo->fileName) }}" alt="" width="100px" >
                              @endif
                            </td>
                            <td>
                              <a href="#" class="btn btn-danger js-deleteBtn" data-id="{{$photo->type}}_{{$photo->fileName}}" >削除する</a>
                              <form id="delete_{{$photo->type}}_{{$photo->fileName}}" action="{{ route('admin.photo.destroy', ['id' => $photo->type.'_'.$photo->fileName]) }}" method="POST" style="display: none;">
                                  @csrf
                                  <input type="hidden" name="type" value="{{$photo->type}}" />
                                  <input type="hidden" name="fileName" value="{{$photo->fileName}}" />
                              </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix ">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    // 削除確認用のダイアログを表示
    $('.js-deleteBtn').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('本当に削除していいですか？')) {
            $('#delete_' + id.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&')).submit();
        }
    });
});
</script>
@endsection
