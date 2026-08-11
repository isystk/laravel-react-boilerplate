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
- 早期returnを優先する
- ネストを浅く保つ
- マジックナンバーを避ける
- コメントよりコードで意図を表現する

## Design

- 単一責任を意識する
- Value Object・Enum・DTOを優先する
- 配列によるデータ受け渡しを避ける
- グローバル状態やstaticの乱用を避ける

## Exceptions

- Exceptionを握りつぶさない
- 必要な例外のみcatchする

## Quality

- 重複コードを作らない
- 小さなメソッドを心掛ける
- 可読性を最優先する

# Laravel

以下はLaravelを利用したプロジェクトのみに適用する。

- Service層にビジネスロジックを書く。Controllerは薄くする（リクエスト受付とレスポンス整形のみ）
- ドメインロジックはDomain/Servicesに配置し、Eloquentモデルに複雑なロジックを持たせない
- 外部APIやファイルI/OはFileIO/Services配下に分離し、Controllerから直接呼ばない
- Requestバリデーションは必ずFormRequestクラスに書く。Controller内でのバリデーション禁止
- DTOで層をまたぐデータを受け渡す場合は`Dto`配下に定義する
- 例外はLaravel標準の例外クラスまたは`Exceptions`配下のカスタム例外を使う。生の`Exception`をthrowしない
- Enumは`Enums`配下に集約し、マジックナンバー・マジックストリングを直書きしない
- DB関連ルール（N+1・Migration等）は`database.md`に従う
- 本ファイルと`documents/laravel_cording_rule.md`が異なる場合は`documents/laravel_cording_rule.md`を優先する
