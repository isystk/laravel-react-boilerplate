<?php

namespace App\Domain\Entities;

use App\Enums\AdminRole;
use Database\Factories\Domain\Entities\AdminFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property string      $password
 * @property AdminRole   $role
 * @property string|null $remember_token
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
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
     * 権限がシステム管理者の場合にTrueを返却する
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === AdminRole::SuperAdmin;
    }

    /**
     * 権限が管理者の場合にTrueを返却する
     */
    public function isManager(): bool
    {
        return $this->role === AdminRole::Staff;
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role'       => AdminRole::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
