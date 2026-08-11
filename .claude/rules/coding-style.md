---
description: 命名・コメント方針
alwaysApply: true
---

# Coding Style

- 命名は言語・プロジェクトの既存規約に合わせる（PHP: camelCase/PascalCase、TS: camelCase、Python: snake_case）
- 1関数1責務。50行を超えたら分割を検討する
- マジックナンバー・マジックストリングはEnum/定数化する
- フォーマットは各プロジェクトのLinter/Formatter設定（ESLint, PHP-CS-Fixer等）に従う。手動整形で崩さない
- 未使用のimport・変数は残さない

## Comments

- クラスのDocに1行で簡潔に役割を書く
- 関数のDoc（docstring/PHPDoc/JSDoc等）に1行で簡潔に処理概要を書く
- 関数内コメントは基本的に不要。コードを読めば分かる内容はコメントしない
- 特に注意が必要な場合（非直感的な実装・トリッキーな回避策等）のみ、例外的に「なぜそうしたか」を関数内コメントに書く
- 関数内の処理が複雑な場合は、関数のDocに処理の流れを箇条書きで追加する
- Booleanを返却する関数のDocは「Xxxxxの場合にTrueを返却する」の形式で記載する
