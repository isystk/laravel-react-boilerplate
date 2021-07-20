<?php
/**
 * admin
 */

// Home
Breadcrumbs::for('admin.home', function ($trail) {
  $trail->push(
      'HOME',
      'admin.home',
      []
  );
});

// Home > 商品一覧
Breadcrumbs::for('admin.stock', function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('stock.Stock List'), 'admin.stock');
});

// Home > 商品一覧 > 商品登録
Breadcrumbs::for('admin.stock.create', function ($breadcrumbs) {
  $breadcrumbs->parent('admin.stock');
  $breadcrumbs->push(__('stock.Stock Regist'), 'admin.stock.create');
});

// Home > 商品一覧 > 商品詳細
Breadcrumbs::for('admin.stock.show', function ($breadcrumbs, $stock) {
  $breadcrumbs->parent('admin.stock');
  $breadcrumbs->push(
    $stock->name,
    'admin.stock.show',
    [
      'params' => [
          'id' => $stock->id,
      ],
    ]);
});

// Home > 商品一覧 > 商品詳細 > 商品編集
Breadcrumbs::for('admin.stock.edit', function ($breadcrumbs, $stock) {
  $breadcrumbs->parent('admin.stock.show', $stock);
  $breadcrumbs->push(
    $stock->name . __('common.Of Change'),
    'admin.stock.edit',
    [
      'params' => [
          'id' => $stock->id,
      ],
    ]);
});

// Home > 注文履歴一覧
Breadcrumbs::for('admin.order', function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('order.Order List'), 'admin.order');
});

// Home > 注文履歴一覧 > 注文履歴詳細
Breadcrumbs::for('admin.order.show', function ($breadcrumbs, $order) {
  $breadcrumbs->parent('admin.order');
  $breadcrumbs->push(
    __('order.Order ID:') . $order->id,
    'admin.order.show',
    [
      'params' => [
          'id' => $order->id,
      ],
    ]);
});

// Home > 顧客一覧
Breadcrumbs::for('admin.user', function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('user.User List'), 'admin.user');
});

// Home > 顧客一覧 > 顧客詳細
Breadcrumbs::for('admin.user.show', function ($breadcrumbs, $user) {
  $breadcrumbs->parent('admin.user');
  $breadcrumbs->push(
    $user->name,
    'admin.user.show',
    [
      'params' => [
          'id' => $user->id,
      ],
    ]);
});

// Home > 顧客一覧 > 顧客詳細 > 顧客編集
Breadcrumbs::for('admin.user.edit', function ($breadcrumbs, $user) {
  $breadcrumbs->parent('admin.user.show', $user);
  $breadcrumbs->push(
    $user->name . 'の変更',
    'admin.user.edit',
    [
      'params' => [
          'id' => $user->id,
      ],
    ]);
});


// Home > お問い合わせ一覧
Breadcrumbs::for('admin.contact', function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('contact.Contact List'), 'admin.contact');
});

// Home > お問い合わせ一覧 > お問い合わせ詳細
Breadcrumbs::for('admin.contact.show', function ($breadcrumbs, $contact) {
  $breadcrumbs->parent('admin.contact');
  $breadcrumbs->push(
    __('contact.Contact ID:') . $contact->id,
    'admin.contact.show',
    [
      'params' => [
          'id' => $contact->id,
      ],
    ]);
});

// Home > お問い合わせ一覧 > お問い合わせ詳細 > お問い合わせ編集
Breadcrumbs::for('admin.contact.edit', function ($breadcrumbs, $contact) {
  $breadcrumbs->parent('admin.contact.show', $contact);
  $breadcrumbs->push(
    __('contact.Contact ID:') . $contact->id . __('common.Of Change'),
    'admin.contact.edit',
    [
      'params' => [
          'id' => $contact->id,
      ],
    ]);
});

// Home > 画像一覧
Breadcrumbs::for('admin.photo', function ($breadcrumbs) {
  $breadcrumbs->parent('admin.home');
  $breadcrumbs->push(__('photo.Photo List'), 'admin.photo');
});
