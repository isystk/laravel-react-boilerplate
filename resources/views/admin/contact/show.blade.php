@extends('layouts.admin')
@section('title', 'お問い合わせID:' . $contact->id)
@section('mainMenu', 'user')
@section('subMenu', 'contact')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.show', $contact) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.contact') }}">前に戻る</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">氏名</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->user_name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">メールアドレス</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->email }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">性別</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->gender->label() }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">年齢</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->age->label() }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">件名</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->title }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">お問い合わせ内容</label>
                <div class="col-sm-10 d-flex align-items-center"
                     style="white-space: pre-wrap;">{{ $contact->contact }}</div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted small">ホームページURL</label>
                <div class="col-sm-10 d-flex align-items-center">
                    @if ($contact->url)
                        <a href="{{ $contact->url }}"
                           target="_blank">{{ $contact->url }}</a>
                    @else
                        -
                    @endif
                </div>
            </div>

            @if ($contact->image)
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label text-muted small">画像</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <img src="{{ $contact->image->getImageUrl() }}"
                             class="img-thumbnail"
                             style="max-width: 200px;" />
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer text-center position-relative">
            <div class="d-inline-block">
                <div class="mx-auto">
                    <a class="btn btn-primary"
                       href="{{ route('admin.contact.edit', ['contact' => $contact]) }}"
                       @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                        変更する
                    </a>
                </div>
            </div>
            <div class="d-inline-block position-absolute"
                 style="right: 30px;">
                <form method="POST"
                      action="{{ route('admin.contact.destroy', ['contact' => $contact]) }}"
                      id="delete_{{ $contact->id }}">
                    @method('DELETE')
                    @csrf
                    <button href="#"
                            class="btn btn-danger js-deleteBtn"
                            data-id="{{ $contact->id }}"
                            @if (!Auth::user()->role->isHighManager()) disabled="disabled" @endif>
                        削除する
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/contact/show.js')
@endsection
