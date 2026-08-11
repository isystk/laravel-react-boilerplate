---
description: UI実装ルール
paths:
  - "**/*.html"
  - "**/*.css"
  - "**/*.scss"
  - "**/*.jsx"
  - "**/*.tsx"
  - "**/*.vue"
alwaysApply: false
---

# UI Development Rules

## Styling

- インラインstyleは使用しない
- CSSフレームワークを優先して利用する
- 既存のデザインシステムやCSS設計を尊重する
- 同じスタイルを複数箇所へ重複定義しない

## CSS Framework

- プロジェクトで採用しているCSSフレームワークを優先する
- Bootstrap、Tailwind CSSなど既存フレームワークのクラスを活用する
- 新規CSSを追加する前に既存クラスで実現できないか確認する

## Component Design

- UIコンポーネントは再利用可能な単位で設計する
- 1つのコンポーネントに過剰な責務を持たせない
- 共通UIはコンポーネント化する
- ページ固有のUIと共通UIを分離する

## Responsive Design

- レスポンシブ対応を考慮する
- 固定サイズを多用しない
- モバイル表示を確認する

## Accessibility

- セマンティックHTMLを使用する
- 適切なalt属性を設定する
- キーボード操作を考慮する
- 色だけで情報を伝えない

## Maintainability

- CSSの場当たり的な追加を避ける
- !importantの使用は避ける
- 命名規則は既存プロジェクトに合わせる
- デザインルールの一貫性を維持する

## Quality

- 実装前に既存UIコンポーネントを確認する
- 既存デザインを壊さない
- 見た目だけでなく保守性を考慮する