<?php

namespace App\Enums;

enum OperationLogType: string
{
    // フロントユーザー操作
    /** ログイン */
    case UserLogin = 'user_login';
    /** ログアウト */
    case UserLogout = 'user_logout';
    /** 商品購入 */
    case UserCheckout = 'user_checkout';
    /** アカウント作成 */
    case UserAccountCreate = 'user_account_create';
    /** アカウント削除 */
    case UserAccountDelete = 'user_account_delete';
    /** プロフィール更新 */
    case UserProfileUpdate = 'user_profile_update';

    // 管理画面スタッフ操作
    /** ログイン */
    case AdminLogin = 'admin_login';
    /** ログアウト */
    case AdminLogout = 'admin_logout';
    /** ユーザー情報更新 */
    case AdminUserUpdate = 'admin_user_update';
    /** ユーザー停止 */
    case AdminUserSuspend = 'admin_user_suspend';
    /** ユーザー有効化 */
    case AdminUserActivate = 'admin_user_activate';
    /** スタッフ作成 */
    case AdminStaffCreate = 'admin_staff_create';
    /** スタッフ更新 */
    case AdminStaffUpdate = 'admin_staff_update';
    /** スタッフ削除 */
    case AdminStaffDelete = 'admin_staff_delete';
    /** 商品作成 */
    case AdminStockCreate = 'admin_stock_create';
    /** 商品更新 */
    case AdminStockUpdate = 'admin_stock_update';
    /** 商品削除 */
    case AdminStockDelete = 'admin_stock_delete';
    /** お問い合わせ返信 */
    case AdminContactReply = 'admin_contact_reply';
    /** お問い合わせ削除 */
    case AdminContactDelete = 'admin_contact_delete';
    /** 画像削除 */
    case AdminImageDelete = 'admin_image_delete';

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return match ($this) {
            self::UserLogin          => 'ログイン',
            self::UserLogout         => 'ログアウト',
            self::UserCheckout       => '商品購入',
            self::UserAccountCreate  => 'アカウント作成',
            self::UserAccountDelete  => 'アカウント削除',
            self::UserProfileUpdate  => 'プロフィール更新',
            self::AdminLogin         => 'ログイン',
            self::AdminLogout        => 'ログアウト',
            self::AdminUserUpdate    => 'ユーザー情報更新',
            self::AdminUserSuspend   => 'ユーザー停止',
            self::AdminUserActivate  => 'ユーザー有効化',
            self::AdminStaffCreate   => 'スタッフ作成',
            self::AdminStaffUpdate   => 'スタッフ更新',
            self::AdminStaffDelete   => 'スタッフ削除',
            self::AdminStockCreate   => '商品作成',
            self::AdminStockUpdate   => '商品更新',
            self::AdminStockDelete   => '商品削除',
            self::AdminContactReply  => 'お問い合わせ返信',
            self::AdminContactDelete => 'お問い合わせ削除',
            self::AdminImageDelete   => '画像削除',
        };
    }
}
