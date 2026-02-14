<?php

namespace App\Domain\Entities;

use App\Enums\PhotoType;
use Database\Factories\Domain\Entities\ImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int       $id
 * @property string    $file_name
 * @property PhotoType $type
 * @property Carbon    $created_at
 * @property Carbon    $updated_at
 */
class Image extends Model
{
    /** @phpstan-use HasFactory<ImageFactory> */
    use HasFactory;

    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'file_name',
        'type',
    ];

    /**
     * S3保存パスを返却します。
     */
    public function getS3Path(): string
    {
        return $this->type->type() . '/' . $this->file_name;
    }

    /**
     * 画像の表示用URLを返却します。
     */
    public function getImageUrl(): string
    {
        return config('app.url') . '/uploads/' . $this->getS3Path();
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type'       => PhotoType::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
