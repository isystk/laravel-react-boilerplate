@extends('layouts.app')

@section('title', 'お問い合わせ登録')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">お問い合わせ登録</div>

                <div class="card-body">
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

                    <form method="POST" action="{{route('contact.store')}}">
                        @csrf

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">お名前を入力してください<span class="required">必須</span></p>
                                <div class="test-wrap large">
                                    <input type="text" name="your_name" value="{{ old('your_name') }}" placeholder="サンプル太郎" maxlength="100">
                                    <div class="form-bottom"></div>
                                </div>

                            </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">返信先のメールアドレスを入力してください<span class="required">必須</span></p>

                                <div class="test-wrap large">
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="sample@sample.com" maxlength="100">
                                    <div class="form-bottom"></div>
                                </div>

                            </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">性別を教えて下さい<span class="required">必須</span></p>

                                <div class="radio-wrap">
                                    <label>
                                        <input type="radio" name="gender" value="0" {{ "0" == old("gender") ? 'checked="checked"' : '' }}>
                                        <span>女性</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label>
                                        <input type="radio" name="gender" value="1" {{ "1" == old("gender") ? 'checked="checked"' : '' }}>
                                        <span>男性</span>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">年齢<span class="required">必須</span></p>

                                <div class="select-wrap">
                                    <select name="age">
                                        <option value="">選択してください</option>
                                        <option value="1" {{ "1" == old("age") ? 'selected="selected"' : '' }}>～19歳</option>
                                        <option value="2" {{ "2" == old("age") ? 'selected="selected"' : '' }}>20歳～29歳</option>
                                        <option value="3" {{ "3" == old("age") ? 'selected="selected"' : '' }}>30歳～39歳</option>
                                        <option value="4" {{ "4" == old("age") ? 'selected="selected"' : '' }}>40歳～49歳</option>
                                        <option value="5" {{ "5" == old("age") ? 'selected="selected"' : '' }}>50歳～59歳</option>
                                        <option value="6" {{ "6" == old("age") ? 'selected="selected"' : '' }}>60歳～</option>
                                    </select>

                                </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">件名を入力してください<span class="required">必須</span></p>

                                <div class="test-wrap large">
                                    <input type="text" name="title" value="{{ old('title') }}" placeholder="○○について" maxlength="100">
                                    <div class="form-bottom"></div>
                                </div>

                            </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">お問い合わせ内容<span class="required">必須</span></p>

                                <div class="textarea-wrap">
                                    <textarea name="contact" rows="8" cols="80">{{ old('contact') }}</textarea>
                                    <div class="counter"><span class="js-textCounter">500</span>文字</div>
                                </div>

                            </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">ホームページURLを入力してください<span>任意</span></p>

                                <div class="test-wrap large">
                                    <input type="url" name="url" value="{{ old('url') }}" placeholder="https://sample.com" maxlength="100">
                                    <div class="form-bottom"></div>
                                </div>

                            </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <p class="item-name">画像を選択してください<span>任意</span></p>

                                <div class="textarea-wrap" id="drop-zone">
                                    {{--
                                    <p><input type="file" name="imageFile"></p>
                                    <br>
                                    --}}
                                    <p><input id="js-uploadImage" type="file"></p>
                                    <div id="result">

                                        @if (old('imageBase64'))
                                        <img src="{{ old('imageBase64') }}" width="200px" />
                                        <input type="hidden" name="imageBase64" value="{{ old('imageBase64') }}" />
                                        <input type="hidden" name="fileName" value="{{ old('fileName') }}" />
                                        @endif

                                    </div>
                                    <p class="error error-message"></p>
                                </div>

                            </div>
                        </section>

                        <section class="form-section">
                            <div class="form-section-wrap">
                                <div class="checkbox-wrap">
                                    <label>
                                        <input type="checkbox" name="caution" value="1">
                                        <span>注意事項に同意する</span>
                                    </label>
                                </div>
                            </div>
                        </section>


                        <div class="submit-wrap">
                            <input class="submit-btn btn btn-info" type="submit" value="登録する">
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/assets/front/js/contact/create.js') }}" defer></script>

@endsection
