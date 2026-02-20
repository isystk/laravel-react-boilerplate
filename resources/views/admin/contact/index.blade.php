@extends('layouts.admin')
@section('title', 'お問い合わせ一覧')
@section('mainMenu', 'user')
@section('subMenu', 'contact')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact') }}
@endsection

@section('content')
    <div class="card card-purple">
        <div class="card-header">
            <h3 class="card-title">検索条件</h3>
        </div>
        <form action="{{ route('admin.contact') }}"
              method="GET">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-3 row">
                    <label for="user_name"
                           class="col-sm-2 col-form-label fw-bold">表示名</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="user_name"
                               id="user_name"
                               value="{{ request()->user_name }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.contacts.user_name') }}" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="title"
                           class="col-sm-2 col-form-label fw-bold">件名</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ request()->title }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.contacts.title') }}" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="contact_date_from"
                           class="col-sm-2 col-form-label fw-bold">お問い合わせ日時</label>
                    <div class="col-sm-10">
                        <div class="row align-items-center g-2">
                            <div class="col-auto"
                                 style="width: 180px;">
                                <input type="text"
                                       name="contact_date_from"
                                       id="contact_date_from"
                                       value="{{ request()->contact_date_from }}"
                                       class="form-control date-picker"
                                       maxlength="{{ config('const.maxlength.commons.date') }}" />
                            </div>
                            <div class="col-auto text-center">
                                <span>～</span>
                            </div>
                            <div class="col-auto"
                                 style="width: 180px;">
                                <input type="text"
                                       name="contact_date_to"
                                       id="contact_date_to"
                                       value="{{ request()->contact_date_to }}"
                                       class="form-control date-picker"
                                       maxlength="{{ config('const.maxlength.commons.date') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit"
                        class="btn btn-secondary">検索</button>
            </div>
        </form>
    </div>
    <form action="{{ route('admin.contact') }}"
          method="GET"
          id="pagingForm">
        <input type="hidden"
               name="user_name"
               value="{{ request()->user_name }}">
        <input type="hidden"
               name="title"
               value="{{ request()->title }}">
        <input type="hidden"
               name="contact_date_from"
               value="{{ request()->contact_date_from }}">
        <input type="hidden"
               name="contact_date_to"
               value="{{ request()->contact_date_to }}">
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card card-purple">
                <div class="card-header">
                    <h3 class="card-title">検索結果</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['id', 'ID'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['users.name', '表示名'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['title', '件名'],
                                ])
                                @include('admin.parts.sortablelink_th', [
                                    'params' => ['created_at', '登録日時'],
                                ])
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $contact)
                                <tr>
                                    <th>{{ $contact->id }}</th>
                                    <td>{{ $contact->user->name }}</td>
                                    <td class="text-truncate"
                                        style="max-width: 350px;">
                                        {{ $contact->title }}
                                    </td>
                                    <td>{{ $contact->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('admin.contact.show', ['contact' => $contact]) }}">詳細</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer  ">
                    {!! $contacts->links('admin.parts.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
