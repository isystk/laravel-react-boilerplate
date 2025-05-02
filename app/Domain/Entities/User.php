<?php

namespace App\Domain\Entities;

use App\Mails\ResetPasswordToUser;
use App\Mails\VerifyEmailToUser;
use Carbon\Carbon;
use Database\Factories\Domain\Entities\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property int|null $provider_id
 * @property string|null $provider_name
 * @property string|null $name
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @phpstan-use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

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
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

}
