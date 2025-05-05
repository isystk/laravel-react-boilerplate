# React コーディング規約

本プロジェクトでは、一貫性と可読性を高めるため、以下のコーディング規約に従って React コンポーネントを記述します。

---

## 目次

- [基本方針](#基本方針)
- [コンポーネント構成](#コンポーネント構成)
- [命名規則](#命名規則)
- [Hooksの使用](#Hooksの使用)
- [PropsとState](#PropsとState)
- [スタイルの管理](#スタイルの管理)
- [テスト](#テスト)
- [その他](#その他)

---

## 基本方針

- モダンな構文（ES6+）を使用する
- 関数コンポーネントを基本とし、クラスコンポーネントは使用しない
- JSX を使用し、ロジックとビューを近づける
- ESLint と Prettier による静的解析とフォーマッターを導入する

## コンポーネント構成

```cpp
src/
  components/
    ComponentName/
      index.tsx         // エントリーポイント
      ComponentName.tsx // 本体
      ComponentName.test.tsx // テスト（任意）
      ComponentName.module.scss // スタイル（任意）
```

- Atomic Design や Feature Based に応じて構成可
- 1コンポーネント1ファイルまたは1ディレクトリ

## 命名規則

- 要素	命名規則	例
- コンポーネント	パスカルケース	UserCard, LoginForm
- ファイル名	パスカルケース	UserCard.tsx
- 関数・変数名	キャメルケース	handleSubmit
- CSSクラス	BEMまたはモジュールCSS	userCard__title、styles.title
- カスタムHooks	use + キャメルケース	useUserData

## Hooksの使用

- カスタム Hook を積極的に活用してロジックを再利用可能に
- useEffect は副作用のみに使用し、依存配列を正確に管理
- ステート管理には useState, useReducer, useContext を適切に使い分ける

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

- 単体テストには Jest + Testing Library を使用
- コンポーネント単位でテストを記述し、UIの動作とロジックを検証する

```tsx
import { render, screen } from '@testing-library/react';
import UserCard from './UserCard';

test('ユーザー名が表示される', () => {
  render(<UserCard name="山田太郎" />);
  expect(screen.getByText('山田太郎')).toBeInTheDocument();
});
```

## その他

- コメントは必要最小限とし、読みやすいコードを心がける
- 冗長なロジックは関数に切り出す
- Magic Number・文字列のベタ書きは避け、定数化する
