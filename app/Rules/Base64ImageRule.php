<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64ImageRule implements Rule
{
    /**
     * @param array<string> $allowedExtensions
     */
    public function __construct(private readonly array $allowedExtensions = ['jpeg', 'png', 'gif']) {}

    public function passes($attribute, $value): bool
    {
        $regex = '/^data:image\/(' . implode('|', $this->allowedExtensions) . ');base64,/';
        if (preg_match($regex, (string) $value)) {
            $base64Str    = substr((string) $value, strpos((string) $value, ',') + 1);
            $decodedImage = base64_decode($base64Str);
            $imgInfo      = getimagesizefromstring($decodedImage);

            return
                $imgInfo !== false &&
                in_array($imgInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF], true);
        }

        return false;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return ':attributeは画像データとして正しくありません。';
    }
}
