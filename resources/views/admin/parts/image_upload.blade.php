<p>
    <input class="js-uploadImage"
           type="file"
           accept="image/png, image/jpeg">
</p>
<p class="error error-message"></p>
<div class="result">
    <input type="hidden"
           name="{{ $id }}_base_64"
           value="{{ old($id . '_base_64') }}" />
    <input type="hidden"
           name="{{ $id }}_file_name"
           value="{{ old($id . '_file_name', $fileName) }}" />
    @if (old($id . '_base_64'))
        <img src="{{ old($id . '_base_64') }}"
             width="200px" />
        <button type="button"
                class="btn btn-danger btn-sm js-remove-image">
            {{ __('common.Delete') }}
        </button>
    @elseif ($fileName)
        <img src="{{ asset('uploads/' . $photoType->type() . '/' . $fileName) }}"
             width="200px"
             id="{{ $id }}-image">
        <button type="button"
                class="btn btn-danger btn-sm js-remove-image">
            {{ __('common.Delete') }}
        </button>
    @endif
</div>
@vite('resources/assets/admin/js/parts/image_upload.js')
