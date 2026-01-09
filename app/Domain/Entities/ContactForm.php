<?php

namespace App\Domain\Entities;

use App\Enums\Age;
use App\Enums\Gender;
use Database\Factories\Domain\Entities\ContactFormFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property string|null $user_name
 * @property string|null $title
 * @property string|null $email
 * @property string|null $url
 * @property Gender      $gender
 * @property Age         $age
 * @property string|null $contact
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 */
class ContactForm extends Model
{
    /** @phpstan-use HasFactory<ContactFormFactory> */
    use HasFactory;

    protected $table = 'contact_forms';

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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender'     => Gender::class,
            'age'        => Age::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
