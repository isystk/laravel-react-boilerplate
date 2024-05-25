<?php

namespace App\Http\Requests\Admin\ContactForm;

use App\Utils\UploadImage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('high-manager');
    }

    /**
     * This method prepares file instante
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();
        $imageFiles = [];
        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        if ($this->has('imageBase64_1') && $this->imageBase64_1 !== null) {
            $imageFiles[0] = UploadImage::convertBase64($this->imageBase64_1);
        }
        if ($this->has('imageBase64_2') && $this->imageBase64_2 !== null) {
            $imageFiles[1] = UploadImage::convertBase64($this->imageBase64_2);
        }
        if ($this->has('imageBase64_3') && $this->imageBase64_3 !== null) {
            $imageFiles[2] = UploadImage::convertBase64($this->imageBase64_3);
        }
        $this->merge([
            'image_files' => collect($imageFiles)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.contact_forms');
        return [
            'user_name' => [
                'required',
                'string',
                'max:' . $maxlength['user_name']
            ],
            'title' => [
                'required',
                'string',
                'max:' . $maxlength['title']
            ],
            'email' => [
                'required',
                'email',
                'max:' . $maxlength['email']
            ],
            'gender' => [
                'required'
            ],
            'age' => [
                'required'
            ],
            'contact' => [
                'required',
                'string',
                'max:' . $maxlength['contact'],
            ],
            'url' => [
                'url',
                'nullable'
            ],
        ];
    }

    /**
     * 項目名
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_name' => __('contact.Name'),
            'title' => __('contact.Title'),
            'email' => __('contact.EMail'),
            'gender' => __('contact.Gender'),
            'age' => __('contact.Age'),
            'contact' => __('contact.Contact'),
            'url' => __('contact.URL'),
            'imageFile' => __('contact.Image'),
        ];
    }

}
