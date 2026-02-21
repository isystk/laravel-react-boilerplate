<?php

namespace App\Services\Api\Profile;

use App\Domain\Entities\User;
use App\Domain\Repositories\Cart\CartRepositoryInterface;
use App\Domain\Repositories\Order\OrderRepositoryInterface;
use App\Domain\Repositories\Order\OrderStockRepositoryInterface;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Services\Common\ImageService;
use Throwable;

class DestroyService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderStockRepositoryInterface $orderStockRepository,
        private readonly ImageService $imageService
    ) {}

    /**
     * アカウントを削除します。
     *
     * @throws Throwable
     */
    public function destroy(User $user): void
    {
        // 注文に関連するデータを削除
        $this->orderStockRepository->deleteByUserId($user->id);
        $this->orderRepository->deleteByUserId($user->id);

        // カートの削除
        $this->cartRepository->deleteByUserId($user->id);

        // アバター画像の削除
        if ($user->avatarImage) {
            $this->userRepository->update([
                'avatar_image_id' => null,
            ], $user->id);
            $this->imageService->delete($user->avatarImage);
        }

        // ユーザーの削除
        $this->userRepository->delete($user->id);
    }
}
