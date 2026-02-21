<?php

namespace App\Enums;

enum OperationLogType: string
{
    // フロントユーザー操作
    /** ログイン */
    case UserLogin         = 'user_login';
    /** ログアウト */
    case UserLogout        = 'user_logout';
    /** 退会 */
    case UserCheckout      = 'user_checkout';
    /** プロフィール更新 */
    case UserProfileUpdate = 'user_profile_update';
    /** プロフィール削除 */
    case UserProfileDelete = 'user_profile_delete';

    // 管理画面スタッフ操作
    /** ユーザー情報更新 */
    case AdminUserUpdate    = 'admin_user_update';
    /** ユーザー停止 */
    case AdminUserSuspend   = 'admin_user_suspend';
    /** ユーザー有効化 */
    case AdminUserActivate  = 'admin_user_activate';
    /** スタッフ作成 */
    case AdminStaffCreate   = 'admin_staff_create';
    /** スタッフ更新 */
    case AdminStaffUpdate   = 'admin_staff_update';
    /** スタッフ削除 */
    case AdminStaffDelete   = 'admin_staff_delete';
    /** 商品作成 */
    case AdminStockCreate   = 'admin_stock_create';
    /** 商品更新 */
    case AdminStockUpdate   = 'admin_stock_update';
    /** 商品削除 */
    case AdminStockDelete   = 'admin_stock_delete';
    /** お問い合わせ返信 */
    case AdminContactReply  = 'admin_contact_reply';
    /** お問い合わせ削除 */
    case AdminContactDelete = 'admin_contact_delete';

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return match ($this) {
            self::UserLogin         => 'ログイン',
            self::UserLogout        => 'ログアウト',
            self::UserCheckout      => '退会',
            self::UserProfileUpdate => 'プロフィール更新',
            self::UserProfileDelete => 'プロフィール削除',
            self::AdminUserUpdate   => 'ユーザー情報更新',
            self::AdminUserSuspend  => 'ユーザー停止',
            self::AdminUserActivate => 'ユーザー有効化',
            self::AdminStaffCreate  => 'スタッフ作成',
            self::AdminStaffUpdate  => 'スタッフ更新',
            self::AdminStaffDelete  => 'スタッフ削除',
            self::AdminStockCreate  => '商品作成',
            self::AdminStockUpdate  => '商品更新',
            self::AdminStockDelete  => '商品削除',
            self::AdminContactReply  => 'お問い合わせ返信',
            self::AdminContactDelete => 'お問い合わせ削除',
        };
    }
}
