<?php

namespace App\Domain\Entities;

use App\Enums\AdminRole;
use Carbon\Carbon;
use Database\Factories\Domain\Entities\AdminFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Admin extends Authenticatable
{
    /** @phpstan-use HasFactory<AdminFactory> */
    use HasFactory;
    use Notifiable;

    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * 権限が上位管理者の場合にTrueを返却する
     */
    public function isHighManager(): bool
    {
        return AdminRole::HighManager->value === $this->role;
    }

    /**
     * 権限が管理者の場合にTrueを返却する
     */
    public function isManager(): bool
    {
        return AdminRole::Manager->value === $this->role;
    }

}
