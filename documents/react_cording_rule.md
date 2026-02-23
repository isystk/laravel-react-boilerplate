# React コーディング規約

本プロジェクトでは、一貫性と可読性を高めるため、Reactを使用したアプリケーションの開発において以下のコーディング規約に従って記述します。

---

## 目次

- [基本方針](#基本方針)
- [コードフォーマット](#コードフォーマット)
- [命名規則](#命名規則)
- [型安全](#型安全)
- [コンポーネント構成](#コンポーネント構成)
- [状態管理](#状態管理)
- [Hooks](#Hooks)
- [スタイルの管理](#スタイルの管理)
- [テスト](#テスト)
- [その他](#その他)

---

## 基本方針

- モダンな構文（ES6+）を使用する
- 拡張子は、`ts` または `tsx` とする
- テストコードが正常に動作し、ビルドが通る状態でコミットする
- ESLint + Prettier を導入し、自動整形・静的解析を行う。

---

## コードフォーマット

- インデントは **スペース2つ**を使用し、タブは禁止します。
- ステートメントの末尾にセミコロンを常に付与します。
- 文字列にはシングルクォート（'）を統一的に利用します。
- 配列やオブジェクトなどで最後の要素にもカンマを付けます。
- アロー関数の引数が1つの場合、括弧を省略します。
- 1行の最大文字数を100文字に制限します。
- 属性は、長い場合は改行してインデントします。

```typescript jsx
<MyComponent
    title="タイトル"
    description="説明文"
    onClick={handleClick}
/>
```

---

## 命名規則

| 要素             | 命名規則                                    | 例                          |
| -------------- |-----------------------------------------|---------------------------|
| コンポーネント        | ディレクトリ + `/index.tsx` | `Button/index.tsx`        |
| Storybook ファイル | ディレクトリ + `index.stories.tsx`            | `Button/index.stories.tsx` |
| テストファイル        | ディレクトリ + `index.test.tsx`               | `Button/index.test.tsx`   |
| CSSクラス         | ディレクトリ + `styles.module.scss`           | `Button/styles.module.scss` |
| カスタム Hooks     | `use` + キャメルケース                         | `useAppData`              |
| ファイル名          | キャメルケース（camelCase）                     | `app.ts`                  |
| 関数・変数名         | キャメルケース（camelCase）                      | `handleSubmit`            |


---

# 型安全

- 全てのPropsに対して詳細な型注釈を付加する。
- Props の型は type を優先する。interface は使用しない。
- nullチェック `??` や オプショナルチェーン `?.` を活用して型安全なアクセスを行う。
- anyの使用は原則禁止。やむを得ない場合は `// eslint-disable` を明記する。

```typescript
type Props = {
  title: string;
  onClick: () => void;
};
const Button: React.FC<Props> = ({ title, onClick }) => (
  <button onClick={onClick}>{title}</button>
);
```

---

## コンポーネント構成

```
resources/assets/front/
  components/
    atoms/
      ComponentName/
        index.tsx         // 本体
        index.stories.tsx // Storybook
        index.test.tsx    // テスト
        styles.module.scss // スタイル（任意）
    interactions/
    molecules/
    organisms/
    templates/
  pages/
    index.tsx             // / ページ用ファイル
    shop/
      index.tsx           // /shop ページ用ファイル
      [id].tsx            // /shop/[id] ページ用ファイル
```

- 1コンポーネントにつき、1ディレクトリを作成する
- 関数コンポーネントを基本とし、クラスコンポーネントは使用しない
- APIの呼び出しは、ページ用ファイルで行い、コンポーネントにはpropsとして渡す
- API呼び出しはServiceに記述する
- コンポーネントの粒度は「Atomic design」に基づく
- コンポーネントは、その責務に応じて以下6つのレベルに分割、作成する。

| 階層             | 役割・例                        |
| -------------- | --------------------------- |
| `atoms`        | 単一要素：ボタン・ラベル・入力欄など          |
| `interactions` | UI補助コンポーネント：モーダル、ツールチップなど   |
| `molecules`    | アトムの集合：フォーム、カードなど           |
| `organisms`    | 複合構成要素：ヘッダー、記事リスト、ナビゲーションなど |
| `templates`    | ページの共通レイアウト：スケルトン、レイアウト構造など |
| `pages`        | 実際のルーティング対象：URL単位で構成される画面全体 |


---

## 状態管理

- コンポーネント内の状態は `useState` / `useReducer` を使用する。
- グローバルステートには Context API を使用する。

### グローバル状態のアーキテクチャ

グローバル状態は以下の3層で構成します。

| クラス | 配置 | 役割 |
| --- | --- | --- |
| `XxxState` | `states/xxx.ts` | 各ドメインの状態を保持するクラス |
| `RootState` | `states/root.ts` | 全 State クラスをまとめたルート状態 |
| `XxxService` | `services/xxx.ts` | 状態変更と外部API通信のロジックを担当 |
| `MainService` | `services/main.ts` | 全 Service を束ねるコンテナ。ローディング・トースト表示も管理 |

```typescript
// states/auth.ts - 認証状態を保持するクラス
export default class AuthState {
  name: string | null;
  email: string | null;

  get isLogined(): boolean {
    return !!this.email;
  }
}

// states/root.ts - 全 State を束ねるルート状態
export default class RootState {
  public isLoading: boolean;
  public toastMessage: string | null;
  public auth: AuthState;
  public cart: CartState;
  // ...
}

// services/main.ts - 全 Service のコンテナ
export default class MainService {
  public auth: AuthService;
  public stock: StockService;
  public cart: CartService;
  // ...

  public showLoading() { ... }
  public showToastMessage(message: string) { ... }
}
```

コンポーネントからは `useAppRoot()` Hook を通じて状態とサービスにアクセスします。

```typescript
const { state, service } = useAppRoot();

// 状態の参照
const isLogined = state.auth.isLogined;

// サービスの呼び出し
await service.stock.readStocks(pageNo);
```

## Hooks

- 独自Hooksには `use` を接頭辞として付ける。
- Hooksは常にトップレベルで呼び出す。

```typescript
const { state, service } = useAppRoot();
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
