# React コーディング規約

本プロジェクトでは、一貫性と可読性を高めるため、Reactを使用したアプリケーションの開発において以下のコーディング規約に従って記述します。

---

## 目次

- [基本方針](#基本方針)
- [コードフォーマット](#コードフォーマット)
- [命名規則](#命名規則)
- [型定義](#型定義)
- [コンポーネント構成](#コンポーネント構成)
- [PropsとState](#PropsとState)
- [スタイルの管理](#スタイルの管理)
- [テスト](#テスト)
- [その他](#その他)

---

## 基本方針

- モダンな構文（ES6+）を使用する
- 拡張子は、`ts` または `tsx` とする
- テストコードが正常に動作し、ビルドが通る状態でコミットする
- TSC による型チェック と Prettier によるフォーマッターを実施する

---

## コードフォーマット

- インデントは **スペース2つ**を使用し、タブは禁止します。
- ステートメントの末尾にセミコロンを常に付与します。
- 文字列にはシングルクォート（'）を統一的に利用します。
- 配列やオブジェクトなどで最後の要素にもカンマを付けます。
- 1行の最大文字数を100文字に制限します。
- アロー関数の引数が1つの場合、括弧を省略します。

---

## 命名規則

| 要素             | 命名規則                                    | 例                                 |
| -------------- |-----------------------------------------| --------------------------------- |
| ファイル名          | パスカルケース（PascalCase）                     | `AuthCheck.tsx`                   |
| 関数・変数名         | キャメルケース（camelCase）                      | `handleSubmit`                    |
| カスタム Hooks     | `use` + キャメルケース                         | `useAppData`                      |
| コンポーネント        | ディレクトリ + `/index.tsx` | `Button/index.tsx`           |
| Storybook ファイル | ディレクトリ + `index.stories.tsx`            | `Button/index.stories.tsx`        |
| テストファイル        | ディレクトリ + `index.test.tsx`               | `Button/index.test.tsx`           |
| CSSクラス         | ディレクトリ + `index.module.scss`                    | `Button/index.module.scss` |


---

# 型定義

- 全ての関数には戻り値の型注釈を必須とする
- 全てのPropsに対して詳細な型注釈を付加する

---

## コンポーネント構成

```cpp
src/
  components/
    atoms/
      ComponentName/
        index.tsx         // 本体
        index.stories.tsx // Storybook
        index.test.tsx    // テスト
        index.module.scss // スタイル（任意）
    interactions/
    molecules/
    organisms/
    templates/
  pages/
    shop/
      index.tsx           // /shop ページ用ファイル
      [id].tsx            // /shop/[id] ページ用ファイル
```

- 1コンポーネントにつき、1ディレクトリを作成する
- 関数コンポーネントを基本とし、クラスコンポーネントは使用しない
- APIの呼び出しは、ページ用ファイルで行い、コンポーネントにはpropsとして渡す
- API呼び出しはServiceに記述する
- コンポーネントの粒度は「Atomic design」に基づく
- コンポーネントは、その責務に応じて以下7つのレベルに分割、作成する。

| 階層             | 役割・例                        |
| -------------- | --------------------------- |
| `atoms`        | 単一要素：ボタン・ラベル・入力欄など          |
| `interactions` | UI補助コンポーネント：モーダル、ツールチップなど   |
| `molecules`    | アトムの集合：フォーム、カードなど           |
| `organisms`    | 複合構成要素：ヘッダー、記事リスト、ナビゲーションなど |
| `templates`    | ページの共通レイアウト：スケルトン、レイアウト構造など |
| `pages`        | 実際のルーティング対象：URL単位で構成される画面全体 |


---

## PropsとState

- 必要以上に状態を持たない「プレゼンテーショナルコンポーネント」を推奨
- Props の型は interface または type で明確に定義 
- children の扱いに注意し、型定義する

```tsx
type Props = {
  title: string;
  onClick?: () => void;
  children?: React.ReactNode;
};
```

---

## スタイルの管理

- スタイルの定義には、CSS Modules の機能を積極的に活用する
- ライブラリとして Tailwind CSS を採用しているが、補助程度の利用に留める
- グローバル CSS は、すべてのページに共通するもののみを定義する (フォント種類、色の定義、レイアウトの基本設定 など)

---

## テスト

- 単体テストには Vitest + Testing Library を使用
- すべてのコンポーネントに対して作成する
- StoryBookのストーリー単位でテストを記述する
- StoryBook でのプレビューとテスト結果を比較し、一致することを確認する

```tsx
import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';
const { Default } = composeStories(stories);

describe('Header Storybook Tests', () => {
    it('ユーザー名が表示される', () => {
        render(<Default />);
        expect(screen.getByText('山田太郎')).toBeInTheDocument();
    });
});
```

---

## その他

- コメントは必要最小限とし、読みやすいコードを心がける
- 冗長なロジックは関数に切り出す
- Magic Number・文字列のベタ書きは避け、定数化する

```javascript
const DEFAULT_LIMIT = 10;
const API_ENDPOINT = 'https://example.com';
```

