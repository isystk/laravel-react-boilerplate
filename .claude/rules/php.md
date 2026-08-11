---
description: PHP/Laravelコーディング規約
paths:
  - "**/*.php"
alwaysApply: false
---

# PHP Rules

## Language

- PHP 8.3以上を前提とする
- `declare(strict_types=1);` を必須とする
- 型宣言（引数・戻り値・プロパティ）は必須
- readonly を利用できる場合は積極的に利用する

## Style

- PSR-12に従う

## Design

- Value Object・Enum・DTOを優先する
- 配列によるデータ受け渡しを避ける

言語非依存の設計・コメント・例外方針は`coding-style.md`に従う。

# Laravel

以下はLaravelを利用したプロジェクトのみに適用する。

- Service層にビジネスロジックを書く。Controllerは薄くする（リクエスト受付とレスポンス整形のみ）
- ドメインロジックはDomain/Servicesに配置し、Eloquentモデルに複雑なロジックを持たせない
- 外部APIやファイルI/OはFileIO/Services配下に分離し、Controllerから直接呼ばない
- Requestバリデーションは必ずFormRequestクラスに書く。Controller内でのバリデーション禁止
- 入力バリデーションを伴うAction（store/update等）には`StoreRequest`/`UpdateRequest`等の専用FormRequestクラスを1対1で作成する
- DTOで層をまたぐデータを受け渡す場合は`Dto`配下に定義する
- 例外はLaravel標準の例外クラスまたは`Exceptions`配下のカスタム例外を使う。生の`Exception`をthrowしない
- Enumは`Enums`配下に集約し、マジックナンバー・マジックストリングを直書きしない
- Enumは`HasLabel`のような共通interfaceを実装し、`label()`メソッド経由で`__('enums.クラス名_値')`形式の翻訳キーからラベルを取得する。ラベル文字列を直書きしない
- DB関連ルール（N+1・Migration等）は`database.md`に従う
- Controller・Job・BatchはRepositoryやEloquentを直接呼ばず、必ずServiceを経由する
- Serviceのインスタンス化は`app(XxxService::class)`を使う
- Serviceは呼び出し元（ControllerのAction・Job・Batch）に対して1対1で作成する
- Serviceはコンストラクタで`XxxRepositoryInterface`をインジェクションし、Eloquentモデルを直接呼ばない
- Entity（Eloquentモデル）は親への`belongsTo`は許可するが、子への`hasMany`は避け、子のRepositoryを経由して取得する
- テストで`factory()`を使う場合は`tests/BaseTest.php`の`createDefaultXxx()`ヘルパー経由で呼び出す。直接`Model::factory()`を書かない
- 追加した関数には対応するテストコードを必ず作成する
- クラス・メソッドには簡潔なPHPDocコメントを付ける（テストコードは不要）
- 処理が複雑な関数のみ、PHPDocに処理の流れを箇条書きで追記する
- Larastan（level 6）でエラーが出ないよう型定義を書く
- 配列リテラルは値ごとに改行して記述する（横並びで書かない）
