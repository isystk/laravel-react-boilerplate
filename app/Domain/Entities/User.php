<?php

namespace App\Domain\Entities;

use App\Mails\ResetPasswordToUser;
use App\Mails\VerifyEmailToUser;
use Database\Factories\Domain\Entities\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int         $id
 * @property string|null $name
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $google_id
 * @property string|null $avatar_url
 * @property string|null $remember_token
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens;

    /** @phpstan-use HasFactory<UserFactory> */
    use HasFactory;

    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'provider_id',
        'provider_name',
        'name',
        'email',
        'email_verified_at',
        'password',
        'google_id',
        'avatar_url',
        'remember_token',
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
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }
}
