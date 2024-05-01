<?php
/**
 * admin
 */

// Home
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('admin.home', static function ($trail) {
  $trail->push(
      'HOME',
      'admin.home',
      []
  );
});

// Home > 商品一覧
Breadcrumbs::for('admin.stock', static function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('stock.Stock List'), 'admin.stock');
});

// Home > 商品一覧 > 商品登録
Breadcrumbs::for('admin.stock.create', static function ($breadcrumbs) {
  $breadcrumbs->parent('admin.stock');
  $breadcrumbs->push(__('stock.Stock Regist'), 'admin.stock.create');
});

// Home > 商品一覧 > 商品詳細
Breadcrumbs::for('admin.stock.show', static function ($breadcrumbs, $stock) {
  $breadcrumbs->parent('admin.stock');
  $breadcrumbs->push(
    $stock->name,
    'admin.stock.show',
    [
      'params' => [
          'stock' => $stock,
      ],
    ]);
});

// Home > 商品一覧 > 商品詳細 > 商品編集
Breadcrumbs::for('admin.stock.edit', static function ($breadcrumbs, $stock) {
  $breadcrumbs->parent('admin.stock.show', $stock);
  $breadcrumbs->push(
    $stock->name . __('common.Of Change'),
    'admin.stock.edit',
    [
      'params' => [
          'stock' => $stock,
      ],
    ]);
});

// Home > 注文履歴一覧
Breadcrumbs::for('admin.order', static function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('order.Order List'), 'admin.order');
});

// Home > 注文履歴一覧 > 注文履歴詳細
Breadcrumbs::for('admin.order.show', static function ($breadcrumbs, $order) {
  $breadcrumbs->parent('admin.order');
  $breadcrumbs->push(
    __('order.Order ID:') . $order->id,
    'admin.order.show',
    [
      'params' => [
          'order' => $order,
      ],
    ]);
});

// Home > 顧客一覧
Breadcrumbs::for('admin.user', static function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('user.User List'), 'admin.user');
});

// Home > 顧客一覧 > 顧客詳細
Breadcrumbs::for('admin.user.show', static function ($breadcrumbs, $user) {
  $breadcrumbs->parent('admin.user');
  $breadcrumbs->push(
    $user->name,
    'admin.user.show',
    [
      'params' => [
          'user' => $user,
      ],
    ]);
});

// Home > 顧客一覧 > 顧客詳細 > 顧客編集
Breadcrumbs::for('admin.user.edit', static function ($breadcrumbs, $user) {
  $breadcrumbs->parent('admin.user.show', $user);
  $breadcrumbs->push(
    $user->name . 'の変更',
    'admin.user.edit',
    [
      'params' => [
          'user' => $user,
      ],
    ]);
});


// Home > お問い合わせ一覧
Breadcrumbs::for('admin.contact', static function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('contact.Contact List'), 'admin.contact');
});

// Home > お問い合わせ一覧 > お問い合わせ詳細
Breadcrumbs::for('admin.contact.show', static function ($breadcrumbs, $contact) {
  $breadcrumbs->parent('admin.contact');
  $breadcrumbs->push(
    __('contact.Contact ID:') . $contact->id,
    'admin.contact.show',
    [
      'params' => [
          'contact' => $contact,
      ],
    ]);
});

// Home > お問い合わせ一覧 > お問い合わせ詳細 > お問い合わせ編集
Breadcrumbs::for('admin.contact.edit', static function ($breadcrumbs, $contact) {
  $breadcrumbs->parent('admin.contact.show', $contact);
  $breadcrumbs->push(
    __('contact.Contact ID:') . $contact->id . __('common.Of Change'),
    'admin.contact.edit',
    [
      'params' => [
          'contact' => $contact,
      ],
    ]);
});

// Home > 画像一覧
Breadcrumbs::for('admin.photo', static function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('photo.Photo List'), 'admin.photo');
});
