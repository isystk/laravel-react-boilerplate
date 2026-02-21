<?php

namespace App\Domain\Entities;

use App\Enums\UserStatus;
use App\Mails\ResetPasswordToUser;
use App\Mails\VerifyEmailToUser;
use Database\Factories\Domain\Entities\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property Carbon|null $email_verified_at
 * @property string|null $password
 * @property int|null    $avatar_image_id
 * @property string|null $google_id
 * @property string|null $avatar_url
 * @property string|null $remember_token
 * @property UserStatus  $status
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Image|null $avatarImage
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens;

    /** @phpstan-use HasFactory<UserFactory> */
    use HasFactory;

    use Notifiable;
    use SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'avatar_image_id',
        'google_id',
        'avatar_url',
        'remember_token',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return BelongsTo<Image, $this>
     */
    public function avatarImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'avatar_image_id');
    }

    /**
     * メール認証済みの場合にTrueを返却する
     */
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * パスワードリセット時に送信するメールオブジェクトを返却する
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordToUser($this, $token));
    }

    /**
     * 新規会員登録時に送信するメールオブジェクトを返却する
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailToUser($this));
    }

    /**
     * JWTの識別子を取得する
     */
    public function getJWTIdentifier(): string
    {
        return (string) $this->getKey();
    }

    /**
     * JWTのカスタムペイロードを取得する
     *
     * @return array<int, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'status'            => UserStatus::class,
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
            'deleted_at'        => 'datetime',
        ];
    }
}
