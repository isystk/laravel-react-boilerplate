<?php

namespace App\Domain\Entities;

use App\Enums\Age;
use App\Enums\Gender;
use Carbon\Carbon;
use Database\Factories\Domain\Entities\ContactFormFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $user_name
 * @property string|null $title
 * @property string|null $email
 * @property string|null $url
 * @property bool $gender
 * @property string|null $contact
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ContactForm extends Model
{
    /** @phpstan-use HasFactory<ContactFormFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_name',
        'title',
        'email',
        'url',
        'gender',
        'age',
        'contact',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return ?Gender
     */
    public function getGender(): ?Gender
    {
        return Gender::get((int)$this->gender);
    }

    /**
     * @return ?Age
     */
    public function getAge(): ?Age
    {
        return Age::get($this->age);
    }


}
