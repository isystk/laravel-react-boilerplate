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

## Async

- Promiseより`async/await`を優先する
- 非同期エラーは適切に処理する
- `Promise.all()`を利用できる場合は並列処理を検討する

## Error Handling

- `catch`では適切にログ出力または再throwを検討する

## Performance

- 不要な再計算を避ける
- 不要なループや配列コピーを避ける
- 可読性を損なう過度な最適化は行わない

## Imports

- import順序はESLint設定に従う

## Quality

- 型安全性を優先する

言語非依存の設計・コメント・例外方針は`coding-style.md`に従う。

# React

以下はReactを利用したプロジェクトのみに適用する。

- コンポーネントは1ファイル1責務。300行を超えたら分割を検討する
- ロジックはカスタムHooksに切り出し、コンポーネント本体はレンダリングに専念させる
- propsの型は必ずTypeScriptで明示する。`any`禁止
- `useEffect`の依存配列は省略せず正確に書く。ESLintの`exhaustive-deps`警告を無視しない
- 状態はコンポーネントローカルを基本とし、複数コンポーネントで共有する場合のみ上位に持ち上げる／Context化する
- 一覧描画には必ず安定した`key`を指定する（index key禁止、配列の並び替えがある場合は特に）
- スタイルは既存プロジェクトの方式（CSS Modules / Tailwind等）に合わせ、混在させない
- コンポーネントは`{Category}/{ComponentName}/`ディレクトリを1単位とし、`index.tsx`（実装）・`index.test.tsx`（テスト）・`index.stories.tsx`（Storybook）・`styles.module.scss`（CSS Modules）の4点セットで構成する
- Remotion（script-to-reel）ではコンポーネントを`useCurrentFrame`ベースで純粋に保ち、副作用を持たせない
