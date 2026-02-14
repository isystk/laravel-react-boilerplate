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
                           class="col-sm-2 col-form-label">氏名</label>
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
                           class="col-sm-2 col-form-label">件名</label>
                    <div class="col-sm-4">
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ request()->title }}"
                               class="form-control"
                               maxlength="{{ config('const.maxlength.contacts.title') }}" />
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
               name="userName"
               value="{{ request()->userName }}">
        <input type="hidden"
               name="title"
               value="{{ request()->title }}">
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
                                    'params' => ['user_name', '氏名'],
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
                                    <td>{{ $contact->user_name }}</td>
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
