# React コーディング規約

本プロジェクトでは、一貫性と可読性を高めるため、以下のコーディング規約に従って React コンポーネントを記述します。

---

## 目次

- [基本方針](#基本方針)
- [コンポーネント構成](#コンポーネント構成)
- [命名規則](#命名規則)
- [Hooksの使用](#Hooksの使用)
- [Service](#Service)
- [PropsとState](#PropsとState)
- [スタイルの管理](#スタイルの管理)
- [テスト](#テスト)
- [その他](#その他)

---

## 基本方針

- モダンな構文（ES6+）を使用する
- インデントは **スペース2つ**を使用し、タブは禁止します。
- コンポーネント名：`StudlyCase`
- 関数・変数名：`camelCase`
- 定数：`UPPER_SNAKE_CASE`
- TypeScript による型チェック と Prettier による静的解析とフォーマッターを実施する
- 関数コンポーネントを基本とし、クラスコンポーネントは使用しない
- API呼び出しなどのロジックはServiceに記述し、コンポーネント間で共用するデータはStateに保存する

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

- 1コンポーネント1ディレクトリ
- コンポーネントの粒度は「Atomic design」に基づく
- コンポーネントは、その責務に応じて以下7つのレベルに分割、作成する。
- APIの呼び出しは、ページ用ファイルで行う(テストコードの記述や保守性の面で有利)

### Atoms（原子）

- 最小単位で最も基本的なコンポーネント（例：ボタン、入力フィールド、ラベル）

### Interactions（相互作用）

- 本来のAtomic Designには存在しない。
- UIインタラクションをコンポーザブルなコンポーネント（例：ツールチップ、モーダル）
- それ以上分解できないものとしてAtomに分類もできるが、煩雑になるので別の層として切り出す

### Molecules（分子）

- 複数のアトムを組み合わせたコンポーネント（例：フォーム、カード）。<br/>

### Organisms（有機体）

- 複数のモレキュールやアトムを組み合わせた、より複雑なUIセクションを形成
- （例：ヘッダー、フッター、ナビゲーションバー、記事リスト）。<br/>

### Templates（テンプレート）

- レイアウトパターンをコンポーネントとして作成する。
- ページ間で共通のタグを記載したHTMLのスケルトン等

### Pages（ページ）

- 画面全体を構成する。
- コンテナとコンポーネントを組み合わせて画面を構成する。
- URLに対応したディレクトリ構成とする。

## 命名規則

- 要素	命名規則	例
- コンポーネント	パスカルケース	UserCard, LoginForm
- ファイル名	パスカルケース	AuthCheck.tsx
- 関数・変数名	キャメルケース	handleSubmit
- CSSクラス	BEMまたはモジュールCSS	userCard__title、styles.title
- カスタムHooks	use + キャメルケース	useAppData

## Hooksの使用

- カスタム Hook を積極的に活用してロジックを再利用可能に
- useEffect は副作用のみに使用し、依存配列を正確に管理
- ステート管理には useState, useReducer, useContext を適切に使い分ける

## Service

- APIの呼び出しなど、外部とのやり取りはすべて Service に切り出す
- ロジックは可能な限り Service 内に書く

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

## スタイルの管理

- スタイリングは以下のいずれかで統一
- CSS Modules（推奨）
- Tailwind CSS
- styled-components（必要に応じて）
- グローバル CSS は極力避け、スコープ化する

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

## その他

- コメントは必要最小限とし、読みやすいコードを心がける
- 冗長なロジックは関数に切り出す
- Magic Number・文字列のベタ書きは避け、定数化する
