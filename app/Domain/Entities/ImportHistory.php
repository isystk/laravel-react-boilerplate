<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use Database\Factories\Domain\Entities\ImportHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $job_id
 * @property int $type
 * @property string $file_name
 * @property int $status
 * @property int $import_user_id
 * @property Carbon|null $import_at
 * @property string $save_file_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ImportHistory extends Model
{
    /** @phpstan-use HasFactory<ImportHistoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'job_id',
        'type',
        'file_name',
        'status',
        'import_user_id',
        'import_at',
        'save_file_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'import_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
