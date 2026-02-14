@extends('layouts.admin')
@section('title', 'お問い合わせID:' . $contact->id . 'の変更')
@section('mainMenu', 'user')
@section('subMenu', 'contact')
@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.contact.edit', $contact) }}
@endsection

@section('content')
    <div class="text-start mb-3">
        <a class="btn btn-secondary"
           href="{{ route('admin.contact.show', ['contact' => $contact]) }}">前に戻る</a>
    </div>

    <div class="card card-purple">
        <div class="card-body">
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
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('admin.contact.update', ['contact' => $contact]) }}">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="user_name"
                           class="form-label">氏名</label>
                    <input type="text"
                           name="user_name"
                           id="user_name"
                           value="{{ old('user_name', $contact->user_name) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contacts.user_name') }}" />
                </div>

                <div class="form-group">
                    <label for="email"
                           class="form-label">メールアドレス</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $contact->email) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contacts.email') }}" />
                </div>
                <div class="form-group">
                    <label class="form-label d-block">性別</label>
                    <div class="ps-2">
                        @foreach (App\Enums\Gender::cases() as $e)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                       type="radio"
                                       name="gender"
                                       id="gender_{{ $e->value }}"
                                       value="{{ $e->value }}"
                                       {{ $e->value == old('gender', $contact->gender->value) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                       for="gender_{{ $e->value }}">
                                    {{ $e->label() }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="age"
                           class="form-label">年齢</label>
                    <select name="age"
                            id="age"
                            class="form-select">
                        <option value="">選択してください</option>
                        @foreach (App\Enums\Age::cases() as $e)
                            <option value="{{ $e->value }}"
                                    {{ $e->value == old('age', $contact->age->value) ? 'selected' : '' }}>
                                {{ $e->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title"
                           class="form-label">件名</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $contact->title) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contacts.title') }}" />
                </div>

                <div class="form-group">
                    <label for="contact"
                           class="form-label">お問い合わせ内容</label>
                    <textarea name="contact"
                              id="contact"
                              rows="10"
                              class="form-control">{{ old('contact', $contact->contact) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="url"
                           class="form-label">ホームページURL</label>
                    <input type="url"
                           name="url"
                           id="url"
                           value="{{ old('url', $contact->url) }}"
                           class="form-control"
                           maxlength="{{ config('const.maxlength.contacts.url') }}" />
                </div>

                <label class="col-sm-2 control-label">画像</label>
                <div class="col-sm-10">
                    @include('admin.parts.image_upload', [
                        'id' => 'image',
                        'fileName' => old('image_file_name', $contact->image?->file_name),
                        'imageUrl' => $contact->image?->getImageUrl(),
                    ])
                </div>

                <div class="card-footer text-center">
                    <input class="btn btn-primary"
                           type="submit"
                           value="登録する">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/assets/admin/js/pages/contact/edit.js')
@endsection
