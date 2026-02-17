<?php

namespace App\Domain\Entities;

use App\Enums\ImageType;
use Database\Factories\Domain\Entities\ImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int       $id
 * @property string    $file_name
 * @property ImageType $type
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
     * IDからハッシュ化されたディレクトリパスを返却します。
     */
    public function getHashedDirectory(): string
    {
        $hash = md5((string) $this->id);

        return substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . substr($hash, 4);
    }

    /**
     * S3保存パスを返却します。
     */
    public function getS3Path(): string
    {
        return $this->type->type() . '/' . $this->getHashedDirectory() . '/' . $this->file_name;
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
            'type'       => ImageType::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * 画像が在庫、問い合わせ、ユーザーのいずれかで使用されている場合にTrueを返却します。
     */
    public function isUsed(): bool
    {
        return $this->used_by_stock_id || $this->used_by_contact_id || $this->used_by_user_id;
    }

    /**
     * 画像が使用されている場合、そのURLを返却します。
     */
    public function getUsedUrl(): ?string
    {
        $usedUrl = null;
        if ($this->used_by_stock_id) {
            $usedUrl = route('admin.stock.show', [
                'stock' => $this->used_by_stock_id,
            ]);
        } elseif ($this->used_by_contact_id) {
            $usedUrl = route('admin.contact.show', [
                'contact' => $this->used_by_contact_id,
            ]);
        } elseif ($this->used_by_user_id) {
            $usedUrl = route('admin.user.show', [
                'user' => $this->used_by_user_id,
            ]);
        }

        return $usedUrl;
    }
}
