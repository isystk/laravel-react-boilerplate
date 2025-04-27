## コンポーネントの作成単位について

コンポーネントの粒度は「Atomic design」に基づき、
その責務に応じて以下7つのレベルに分割、作成する。

- atoms
- molecules
- organisms
- templates

## Atoms（原子）

最小単位で最も基本的なコンポーネント（例：ボタン、入力フィールド、ラベル）。<br/>
components/atomsディレクトリに配置。

## Molecules（分子）

複数のアトムを組み合わせたコンポーネント（例：フォーム、カード）。<br/>
components/moleculesディレクトリに配置。

## Organisms（有機体）

複数のモレキュールやアトムを組み合わせた、より複雑なUIセクションを形成（例：ヘッダー、フッター、ナビゲーションバー、記事リスト）。<br/>
components/organismsディレクトリに配置。

## Templates（テンプレート）

レイアウトパターンをコンポーネントとして作成する。
ページ間で共通のタグを記載したHTMLのスケルトン等

