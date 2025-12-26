<?php

namespace App\Http\Requests\Api\ContactForm;

use App\Enums\Age;
use App\Enums\Gender;
use App\Utils\UploadImage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * This method prepares file instante
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();
        $imageFiles = [];
        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        if ($this->has('imageBase64_1') && null !== $this->imageBase64_1) {
            $imageFiles[] = UploadImage::convertBase64($this->imageBase64_1);
        }
        if ($this->has('imageBase64_2') && null !== $this->imageBase64_2) {
            $imageFiles[] = UploadImage::convertBase64($this->imageBase64_2);
        }
        if ($this->has('imageBase64_3') && null !== $this->imageBase64_3) {
            $imageFiles[] = UploadImage::convertBase64($this->imageBase64_3);
        }
        $this->merge([
            'image_files' => collect($imageFiles),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string|Enum>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.contact_forms');

        return [
            'user_name' => [
                'required',
                'string',
                'max:' . $maxlength['user_name'],
            ],
            'title' => [
                'required',
                'string',
                'max:' . $maxlength['title'],
            ],
            'email' => [
                'required',
                'email',
                'max:' . $maxlength['email'],
            ],
            'gender' => [
                'required',
                new Enum(Gender::class),
            ],
            'age' => [
                'required',
                new Enum(Age::class),
            ],
            'contact' => [
                'required',
                'string',
                'max:' . $maxlength['contact'],
            ],
            'url' => [
                'url',
                'nullable',
            ],
            'caution' => [
                'required',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string>
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
            'image_files' => __('contact.Image'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string>
     */
    public function messages(): array
    {
        return [
            '*.Illuminate\Validation\Rules\Enum' => ':attributeの値が不正です。',
        ];
    }
}
