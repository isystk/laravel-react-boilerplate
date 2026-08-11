---
description: JavaScript/TypeScript・Reactコーディング規約
paths:
  - "**/*.js"
  - "**/*.jsx"
  - "**/*.mjs"
  - "**/*.cjs"
  - "**/*.ts"
  - "**/*.tsx"
alwaysApply: false
---

# JavaScript / TypeScript Rules

## Language

- TypeScriptを優先し、新規実装では可能な限り型を定義する
- `any`の使用は禁止。やむを得ない場合のみ理由をコメントで残す
- `unknown`を優先し、型を絞り込んで利用する
- 型推論を活用し、冗長な型指定は避ける
- `const`を優先し、再代入が必要な場合のみ`let`を使用する
- `var`は使用しない

## Style

- ESLintおよびPrettierのルールに従う
- 早期returnを優先する
- ネストを浅く保つ
- マジックナンバーを避ける
- コメントよりコードで意図を表現する
- 1つの関数・メソッドは単一責任を意識する

## Design

- 関数は小さく保つ
- 重複コードを作らない
- Utility関数を乱立させない
- 共通処理は適切なモジュールへ切り出す
- グローバル状態を増やさない

## Async

- Promiseより`async/await`を優先する
- 非同期エラーは適切に処理する
- `Promise.all()`を利用できる場合は並列処理を検討する

## Error Handling

- エラーを握りつぶさない
- `catch`では適切にログ出力または再throwを検討する
- 空の`catch`ブロックは禁止

## Performance

- 不要な再計算を避ける
- 不要なループや配列コピーを避ける
- 可読性を損なう過度な最適化は行わない

## Imports

- 使用していないimportは削除する
- 循環参照を作らない
- import順序はESLint設定に従う

## Quality

- 型安全性を優先する
- 可読性を最優先する
- 小さな変更を心掛ける
- 既存コードのスタイルに合わせる

# React

以下はReactを利用したプロジェクトのみに適用する。

- コンポーネントは1ファイル1責務。300行を超えたら分割を検討する
- ロジックはカスタムHooksに切り出し、コンポーネント本体はレンダリングに専念させる
- propsの型は必ずTypeScriptで明示する。`any`禁止
- `useEffect`の依存配列は省略せず正確に書く。ESLintの`exhaustive-deps`警告を無視しない
- 状態はコンポーネントローカルを基本とし、複数コンポーネントで共有する場合のみ上位に持ち上げる／Context化する
- 一覧描画には必ず安定した`key`を指定する（index key禁止、配列の並び替えがある場合は特に）
- スタイルは既存プロジェクトの方式（CSS Modules / Tailwind等）に合わせ、混在させない
- Remotion（script-to-reel）ではコンポーネントを`useCurrentFrame`ベースで純粋に保ち、副作用を持たせない
