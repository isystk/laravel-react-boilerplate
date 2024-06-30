🌙 laravel-react-boilerplate
====

[![isystk](https://circleci.com/gh/isystk/laravel-react-boilerplate.svg?style=svg)](https://circleci.com/gh/circleci/circleci-docs)
![GitHub issues](https://img.shields.io/github/issues/isystk/laravel-react-boilerplate)
![GitHub forks](https://img.shields.io/github/forks/isystk/laravel-react-boilerplate)
![GitHub stars](https://img.shields.io/github/stars/isystk/laravel-react-boilerplate)
![GitHub license](https://img.shields.io/github/license/isystk/laravel-react-boilerplate)

## 📗 プロジェクトの概要

Laravel ＆ React.js の学習用サンプルアプリケーションです。


### 対象としている方
- Laravelを初めて学習してみたい方
- Dockerを利用したLaravelの開発環境を構築したい方
- ECサイトを学習してみたい方
- 管理画面も作成（マルチログイン）を作成したい方
- Stripeを利用した決算処理を作成してみたい方
- ソーシャルログインを作成してみたい方
- オブジェクトストレージへの画像アップロードを作成してみたい方
- フロントエンドをReact.jsで作成してみたい方

### 利用している技術

#### ■ インフラ
- Apache 2.4.46　・・・　WebサーバーとしてApacheを採用しました。自己証明書を設定済みなので開発環境でSSLとして動作可能です。
- MySQL 8　・・・　DBサーバーにはMySQLを採用しました。データファイルや設定ファイル、 ログなどはコンテナの外に出して 開発時に参照出来るようにしています。
- phpMyAdmin　・・・　起動したMySQLのデータを参照・編集するためのツールです。
- MailHog 　・・・　ダミーのSMTPサーバーです。送信したメールをブラウザで閲覧することが可能です。実際にはメールは送信されないので開発時の誤送信してしまう心配がありません。
- Minio 　・・・　S3に完全互換性のあるオブジェクトストレージです。アップロードした画像の保存先として利用しています。
- Redis 　・・・　永続化可能なインメモリデータベースです。DBから取得したデータのキャッシュとして利用しています。

#### ■ アプリケーション

- Laravel 11
- React 18
- Typescript
- Adminlte 3
- Bootstrap 4

## 🌐 Demo

#### ■ フロント画面（React）

https://laraec.isystk.com/

![フロント画面](./documents/front.png "フロント画面")

- ログイン/ログアウト
- 会員登録
- パスワードリマインダ
- 商品一覧
- カートに追加
- 決算処理（Stripe）
- お気に入り追加
- お問い合わせ
- ソーシャルログイン（Google）

```mermaid
graph LR

  classDef default fill: #fff,stroke: #333,stroke-width: 1px;
  style funcA fill: #fff,stroke: #333,stroke-width: 1px;
  style funcB fill: #fff,stroke: #333,stroke-width: 1px;
  style funcC fill: #fff,stroke: #333,stroke-width: 1px;
  style funcD fill: #fff,stroke: #333,stroke-width: 1px;
  style header fill: #fff,stroke: #333,stroke-width: 1px;

  TOP-->ログイン--ID/パスワード認証-->マイカート
  TOP-->お問い合わせ入力
  ログイン-->会員仮登録
  ログイン-->パスワードリセット

  subgraph funcA [商品購入]
    マイカート-->商品購入完了
  end

  subgraph funcB [会員登録]
    会員仮登録-->会員仮登録完了--メール送信-->会員登録完了
  end

  subgraph funcC [パスワードリセット]
    パスワードリセット--メール送信-->パスワード変更
  end

  subgraph funcD [お問い合わせ]
    お問い合わせ入力-->お問い合わせ完了
  end

  subgraph header [ヘッダ]
    マイカート
    お問い合わせ入力
    ログアウト
  end
```

#### ■ 管理画面（Bootstrap）

https://laraec.isystk.com/admin/

![管理画面](./documents/admin.png "管理画面")

- ログイン/ログアウト
- 商品管理
- 注文履歴
- 顧客管理
- お問い合わせ管理
- 画像管理
- CSVダウンロード
- PDFダウンロード
- 画像アップロード

#### ■ バッチ処理

- 商品CSV出力バッチ
- S3商品画像アップロードバッチ

## 🔧 開発環境の構築

※ この環境を利用する為には、事前にdocker、docker-composeが動作する状態であることが前提条件です。
(Windowsの場合は、以下を参考に「WSL」と「Docker Desktop for Windows」を用意してください)
Github CodeSpace を利用する場合は、Dockerの起動から進めてください。

### WSLのインストール（Windowsの場合）
参考
https://docs.microsoft.com/ja-jp/windows/wsl/install

WSLでUbuntuを起動する
```
# 初回起動時に、ユーザ名とパスワードが聞かれます。
# 何も入力せずにEnterを押すとroot ユーザーで利用できるようになるので、rootユーザーとして設定します。

# 初めにライブラリを最新化します。
$ apt update

# 日本語に対応しておきます。
$ apt -y install language-pack-ja
$ update-locale LANG=ja_JP.UTF8
$ apt -y install manpages-ja manpages-ja-dev
```

### Docker Desktop for Windows のインストール（Windowsの場合）

https://docs.docker.com/docker-for-windows/install/
```
↓コマンドプロンプトでバージョンが表示されればOK
docker --version
```

### WSL2から、Docker for Windows を利用できるようにする（Windowsの場合）

```
１．Docker Desktop を開いて、Settingを開く
２．Resourcesの「Enable integration with my default WSL distro」にチェックをつけてWSLから Docker 統合を有効にする
３．ResourcesのWSL INTEGRATION から、"Ubuntu" をスイッチをONにします。

# WSL 上にDockerとDocker Composeをインストールする。
$ apt install docker
$ apt install docker-compose

これでWSLで起動したDockerコンテナがホストマシン側で利用できるようになります。
```

### MySQL Client のインストール

```
# MySQLに接続する為のコマンドをインストールします。（バージョンは何でもOK）

# Windowsの場合
$ apt install mysql-client

# Macの場合
$ brew install mysql-client
```

## 📦 ディレクトリ構造

```
.
├── docker （各種Daemon）
│   │
│   ├── apache （Webサーバー）
│   │   ├── conf.d (apacheの設定ファイル)
│   │   └── logs （apacheのログ）
│   ├── mysql （DBサーバー）
│   │   ├── conf.d (mysqlの設定ファイル)
│   │   ├── initdb.d （mysqlの初期DDL）
│   │   └── logs （mysqlのログ）
│   ├── php （PHP-FRM）
│   │   └── logs （phpのログ）
│   ├── phpmyadmin （DB管理ツール）
│   └── s3 （オブジェクトストレージ）
│
├── app
│   ├── Console (バッチ)
│   ├── Domain（ドメイン層）
│   │   ├── Entities（エンティティ）
│   │   └── Repositories（リポジトリー）
│   ├── Enums (定数)
│   ├── Exceptions (例外処理)
│   ├── Http （プレゼンテーション層）
│   ├── Providers（サービスプロバイダー）
│   ├── Services（ビジネスロジック）
│   └── Utils（共通ユーティル）
├── bootstrap
├── config
├── database
├── public
├── resources
│   ├── lang
│   ├── assets
│   │   ├── admin（管理画面用のJavaScript、SASS）
│   │   └── front（フロント画面用のTypeScript,SASS）
│   ├── layouts
│   ├── mails
│   └── vendor
├── routes
├── storage
├── tests
├── composer.json
└── dc.sh （Dockerの起動用スクリプト）
```

## 🖊️ Docker 操作用シェルスクリプトの使い方

```
Usage:
  dc.sh [command] [<options>]

Options:
  stats|st                 Dockerコンテナの状態を表示します。
  init                     Dockerコンテナ・イメージ・生成ファイルの状態を初期化します。
  start                    すべてのDaemonを起動します。
  stop                     すべてのDaemonを停止します。
  apache restart           Apacheを再起動します。
  mysql login              MySQLデータベースにログインします。
  mysql export <PAHT>      MySQLデータベースのdumpファイルをエクスポートします。
  mysql import <PAHT>      MySQLデータベースにdumpファイルをインポートします。
  php login                PHP-FPMのサーバーにログインします。
  --version, -v     バージョンを表示します。
  --help, -h        ヘルプを表示します。
```

### phpMyAdmin 
データベースに接続してデータの参照や編集が可能です。
Dockerを起動後に以下のURLにアクセスすると利用可能です。

http://localhost:8888/

### mailhog 
ダミーのメールサーバーです。実際にはメールは送信されず、送信されたメールはブラウザで閲覧できます。
Dockerを起動後に以下のURLにアクセスすると利用可能です。

http://localhost:8025/

### minio
S3に準拠したダミーのオブジェクトストレージです。
Dockerを起動後に以下のURLにアクセスすると利用可能です。

http://localhost:9001


## 💬 使い方

各種デーモンを起動する
```
# 下準備（初回のみ）
$ ./dc.sh init

# Dockerでローカル環境に各種デーモンを構築・起動する
$ ./dc.sh start

# データベースとPHPが立ち上がるまで少し待ちます。(初回は5分程度)

# MySQLにログインしてみる（ログインが出来れば成功です）
$ ./dc.sh mysql login
```

minioにバケットを作成する

[こちら](http://localhost:9001/)から以下のID/パスワードでログイン後、「laraec.isystk.com」という名前のバケットを作成します。
作成後、Manage から Access Policy を「Public」に変更してバケット内ファイルを外部参照可能な状態に公開します。

|Username | Password
|----|----
|access_key | secret_key

![minio](./documents/minio.png "minio")

バックエンド環境を構築する
```
# PHPサーバーにログインしてみる（composer や artisan などのコマンドは基本的にここで行う）
$ ./dc.sh php login

# .envをコピーする
> cp .env.example .env

# モジュールをダウンロード
> composer update

# encryption keyを生成する
> php artisan key:generate

# テーブルとテストデータの作成
> php artisan migrate:fresh --seed

# ディレクトリにアクセス権限を付与
> chmod 777 -R bootstrap/cache
> chmod 777 -R storage
> chmod 777 -R resources/excel

# テスト用の商品画像をS3（Minio）にアップロードします。※事前に minioをセットアップしておくこと
> php artisan s3upload

# Larastan を実行してコードをチェックする
> ./vendor/bin/phpstan analyse --memory-limit=1G

# PHPUnit でテストコードを実行する
> ./vendor/bin/phpunit tests
```

フロントエンド環境を構築する
```
# PHPサーバーにログインしてみる
$ ./dc.sh php login

# フロントエンドをビルドする
$ yarn && yarn run dev

# Jobキューを起動する
$ php artisan queue:listen --timeout=0;
```

その他（補足）
```
# ブラウザでアクセス（フロント）
$ open https://localhost/

# ブラウザでアクセス（管理画面）
$ open https://localhost/admin/

# バッチを実行する（商品CSV出力バッチ）
$ ./dc.sh php login
$ php artisan stockcsv

# サーバーを停止する場合
$ ./dc.sh stop
```

## 🎨 参考

| プロジェクト                                                                                                  | 概要                               |
|:------------------------------------------------------------------------------------------------------------|:---------------------------------|
| [Laravel11公式ドキュメント](https://readouble.com/laravel/11.x/ja/releases.html)                               | Laravel11公式ドキュメントです。             |
| [Laravel11以降から使い始めた人に必要そうな質問と回答一覧](https://zenn.dev/pcs_engineer/articles/laravel11-faq)      | Laravel11以降から使い始めた人に必要そうな質問と回答一覧 |


## 🎫 Licence

[MIT](https://github.com/isystk/laravel-react-boilerplate/blob/master/LICENSE)

## 👀 Author

[isystk](https://github.com/isystk)
