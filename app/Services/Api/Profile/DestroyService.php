<?php

namespace App\Services\Api\Profile;

use App\Domain\Entities\User;
use App\Domain\Repositories\Cart\CartRepository;
use App\Domain\Repositories\Order\OrderRepository;
use App\Domain\Repositories\Order\OrderStockRepository;
use App\Domain\Repositories\User\UserRepository;
use App\Services\Common\ImageService;
use Throwable;

class DestroyService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CartRepository $cartRepository,
        private readonly OrderRepository $orderRepository,
        private readonly OrderStockRepository $orderStockRepository,
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
