@extends('layouts.app_admin')

@section('title', __('contact.Contact ID:').$contact->id)
@php
$menu = 'user';
$subMenu = 'contact';
@endphp

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.show', $contact) }}
@endsection

@section('content')

<div class="card card-purple">
    <div class="card-body">
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.Name')}}</label>
                <div class="col-sm-12">
                    {{ $contact -> your_name }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.EMail')}}</label>
                <div class="col-sm-12">
                    {{ $contact -> email }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.Gender')}}</label>
                <div class="col-sm-12">
                    {{ App\Enums\Gender::getDescription($contact -> gender)}}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.Age')}}</label>
                <div class="col-sm-12">
                    {{ App\Enums\Age::getDescription($contact -> age)}}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.Title')}}</label>
                <div class="col-sm-12">
                    {{ $contact -> title }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.Contact')}}</label>
                <div class="col-sm-12">
                    {{ $contact -> contact }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.URL')}}</label>
                <div class="col-sm-12">
                    {{ $contact -> url }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="control-group">
                <label class="col-sm-6 control-label">{{__('contact.Image')}}</label>
                <div class="col-sm-12">
                    @foreach($contact -> contactFormImages as $contactFormImage)
                    @if ($contactFormImage['file_name'])
                    <img src="{{ asset('uploads/' . $contactFormImage['file_name']) }}" width="200px">
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-center clearfix ">
        <form method="GET" action="{{route('admin.contact.edit', ['id' => $contact->id ])}}">
            @csrf
            <button type="submit" class="btn btn-info">{{__('common.Change')}}</button>
        </form>
        <form method="POST" action="{{route('admin.contact.destroy', ['id' => $contact->id ])}}" id="delete_{{ $contact->id }}">
            @csrf
            <a href="#" class="btn btn-danger js-deleteBtn" data-id="{{ $contact->id }}" >{{__('common.Delete')}}</a>
        </form>
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
            $('#delete_' + id).submit();
        }
    });
});
</script>
@endsection
