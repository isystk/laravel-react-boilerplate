---
description: 命名・コメント方針
alwaysApply: true
---

# Coding Style

## Naming

- 命名は言語・プロジェクトの既存規約に合わせる（PHP: camelCase/PascalCase、TS: camelCase、Python: snake_case）
- マジックナンバー・マジックストリングはEnum/定数化する

## Design

- 早期returnを優先する
- ネストを浅く保つ
- 1関数1責務を意識する。50行を超えたら分割を検討する
- 重複コードを作らない
- グローバル状態・静的変数の乱用を避ける
- 共通処理は適切なモジュールへ切り出す
- private関数はpublic関数の下に書く

## Formatting

- フォーマットは各プロジェクトのLinter/Formatter設定（ESLint, PHP-CS-Fixer, Ruff/Black等）に従う。手動整形で崩さない
- 未使用のimport・変数は残さない
- 循環参照を作らない

## Exceptions

- 例外を握りつぶさない
- 空のcatch/exceptブロックは禁止する
- 捕捉する例外は可能な限り具体的に指定する

## Comments

- クラスのDocに1行で簡潔に役割を書く
- 関数のDoc（docstring/PHPDoc/JSDoc等）に1行で簡潔に処理概要を書く
- 関数内コメントは基本的に不要。コードを読めば分かる内容はコメントしない
- コメントよりコードで意図を表現する
- 特に注意が必要な場合（非直感的な実装・トリッキーな回避策等）のみ、例外的に「なぜそうしたか」を関数内コメントに書く
- 関数内の処理が複雑な場合は、関数のDocに処理の流れを箇条書きで追加する
- Booleanを返却する関数のDocは「Xxxxxの場合にTrueを返却する」の形式で記載する

## Quality

- 可読性を最優先する
- 既存コードのスタイルに合わせる
- 小さな変更を心掛ける
