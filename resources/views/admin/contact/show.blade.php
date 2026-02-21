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

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-purple mb-4">
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">表示名</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->user->name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">お問い合わせ種類</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->type->label() }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">件名</label>
                <div class="col-sm-10 d-flex align-items-center">
                    {{ $contact->title }}
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-muted fw-bold">お問い合わせ内容</label>
                <div class="col-sm-10 d-flex align-items-center"
                     style="white-space: pre-wrap;">{{ $contact->message }}</div>
            </div>

            @if ($contact->image)
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label text-muted fw-bold">画像</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <img src="{{ $contact->image->getImageUrl() }}"
                             class="img-thumbnail"
                             style="max-width: 200px;" />
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer text-center">
            <div class="d-flex justify-content-end">
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

    {{-- 返信履歴 --}}
    <div class="card card-purple mb-4">
        <div class="card-header">
            <h5 class="mb-0">返信履歴</h5>
        </div>
        <div class="card-body">
            @forelse ($contact->replies as $reply)
                <div class="mb-3 border rounded p-3 bg-light">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold small">{{ $reply->admin->name }}</span>
                        <span class="text-muted small">{{ $reply->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <div style="white-space: pre-wrap;">{{ $reply->body }}</div>
                </div>
            @empty
                <p class="text-muted mb-0">返信履歴はありません。</p>
            @endforelse
        </div>
    </div>

    {{-- 返信フォーム --}}
    <div class="card card-purple">
        <div class="card-header">
            <h5 class="mb-0">返信する</h5>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.contact.reply', ['contact' => $contact]) }}">
                @csrf
                <div class="mb-3">
                    <label for="body"
                           class="form-label fw-bold">返信内容 <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('body') is-invalid @enderror"
                              id="body"
                              name="body"
                              rows="6"
                              maxlength="{{ config('const.maxlength.contact_replies.body') }}"
                              required>{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-primary">
                        送信する（メール通知あり）
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/contact/show.js')
@endsection
