<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;


/**
 * @property int $id
 * @property int $stock_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Cart extends Model
{
    protected $fillable = [
        'stock_id',
        'user_id',
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
     * @return BelongsTo
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * TODO Repositoryに移行する
     * @return array<string, mixed>
     */
    public function showCart()
    {
        $user_id = Auth::id();
        $data['data'] = $this->where('user_id', $user_id)->get();

        $data['count'] = 0;
        $data['sum'] = 0;

        foreach ($data['data'] as $my_cart) {
            $data['count']++;
            $data['sum'] += $my_cart->stock->price;
        }
        return $data;
    }
    /**
     * TODO Repositoryに移行する
     * @param string $stock_id
     * @return string
     */
    public function addCart(string $stock_id): string
    {
        $user_id = Auth::id();
        $cart_add_info = self::firstOrCreate(['stock_id' => $stock_id, 'user_id' => $user_id]);

        if ($cart_add_info->wasRecentlyCreated) {
            $message = 'カートに追加しました';
        } else {
            $message = 'カートに登録済みです';
        }

        return $message;
    }

    /**
     * TODO Repositoryに移行する
     * @param string $stock_id
     * @return string
     */
    public function deleteCart($stock_id): string
    {
        $user_id = Auth::id();
        $delete = $this->where('user_id', $user_id)->where('stock_id', $stock_id)->delete();

        if ($delete > 0) {
            $message = 'カートから選択した商品を削除しました';
        } else {
            $message = '削除に失敗しました';
        }
        return $message;
    }

    /**
     * TODO Repositoryに移行する
     * @return string
     */
    public function deleteMyCart(): string
    {
        $user_id = Auth::id();
        $delete = $this->where('user_id', $user_id)->delete();

        if ($delete > 0) {
            $message = 'カートから選択した商品を削除しました';
        } else {
            $message = '削除に失敗しました';
        }
        return $message;
    }

    /**
     * TODO Repositoryに移行する
     * @return mixed
     */
    public function checkoutCart(): mixed
    {
        // ユーザーのカートを取得する。
        $user_id = Auth::id();
        $checkout_items = $this->where('user_id', $user_id)->get();

        // カートを空にする。
        $this->where('user_id', $user_id)->delete();

        return $checkout_items;
    }
}
