<?php

namespace Tests\Unit\Enums;

use App\Enums\OperationLogType;
use Tests\BaseTest;

class OperationLogTypeTest extends BaseTest
{
    public function test_label_各ケースのラベルが返却されること(): void
    {
        $this->assertSame('ログイン', OperationLogType::UserLogin->label());
        $this->assertSame('ログアウト', OperationLogType::UserLogout->label());
        $this->assertSame('商品をカートに追加', OperationLogType::UserCartAdd->label());
        $this->assertSame('商品をカートから削除', OperationLogType::UserCartDelete->label());
        $this->assertSame('商品決済完了', OperationLogType::UserCheckout->label());
        $this->assertSame('アカウント作成', OperationLogType::UserAccountCreate->label());
        $this->assertSame('アカウント削除', OperationLogType::UserAccountDelete->label());
        $this->assertSame('プロフィール更新', OperationLogType::UserProfileUpdate->label());
        $this->assertSame('お問い合わせ送信', OperationLogType::UserContactSend->label());
        $this->assertSame('ログイン', OperationLogType::AdminLogin->label());
        $this->assertSame('ログアウト', OperationLogType::AdminLogout->label());
        $this->assertSame('ユーザー情報更新', OperationLogType::AdminUserUpdate->label());
        $this->assertSame('ユーザー停止', OperationLogType::AdminUserSuspend->label());
        $this->assertSame('ユーザー有効化', OperationLogType::AdminUserActivate->label());
        $this->assertSame('スタッフ作成', OperationLogType::AdminStaffCreate->label());
        $this->assertSame('スタッフ更新', OperationLogType::AdminStaffUpdate->label());
        $this->assertSame('スタッフ削除', OperationLogType::AdminStaffDelete->label());
        $this->assertSame('スタッフインポート', OperationLogType::AdminStaffImport->label());
        $this->assertSame('商品作成', OperationLogType::AdminStockCreate->label());
        $this->assertSame('商品更新', OperationLogType::AdminStockUpdate->label());
        $this->assertSame('商品削除', OperationLogType::AdminStockDelete->label());
        $this->assertSame('お問い合わせ返信', OperationLogType::AdminContactReply->label());
        $this->assertSame('お問い合わせ削除', OperationLogType::AdminContactDelete->label());
        $this->assertSame('画像削除', OperationLogType::AdminImageDelete->label());
    }
}
