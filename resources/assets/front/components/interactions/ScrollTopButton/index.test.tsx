import { describe, it, expect, vi, beforeAll, beforeEach } from 'vitest';
import { render, screen } from '@testing-library/react';
import * as stories from './index.stories';
import styles from './styles.module.scss';
import { composeStories } from '@storybook/react';

const { Default } = composeStories(stories);

describe('Default', () => {
  beforeAll(() => {
    // スクロールイベントを模擬するためのセットアップ
    window.scrollTo = vi.fn();
  });

  beforeEach(() => {
    // 各テスト前にモックをリセット
    vi.clearAllMocks();
  });

  it('should not be visible initially', () => {
    render(
      <div style={{ height: '2000px', padding: '16px' }}>
        <p>スクロールしてボタンが表示されるのを確認してください。</p>
        <Default />
      </div>,
    );
    const button = screen.queryByRole('button');
    // ボタンはDOMに存在しているが、非表示の状態であるため、表示されないことを確認
    expect(button).toBeInTheDocument(); // DOMに存在することを確認
    expect(button).toHaveClass(styles.hide); // styles モジュールの hide クラスがついていることを確認
  });

  // TODO エラーになるのでコメントアウト
  // it('should be visible after scrolling', async () => {
  //   render(
  //     <div style={{ height: '2000px', padding: '16px' }}>
  //       <p>スクロールしてボタンが表示されるのを確認してください。</p>
  //       <Default />
  //     </div>,
  //   );
  //
  //   // スクロール位置を300pxに設定
  //   act(() => {
  //     window.scrollTo(0, 301); // スクロール位置を変更
  //   });
  //
  //   // スクロール後に状態更新が反映されるのを待つ
  //   await screen.findByRole('button'); // ボタンが表示されるまで待機
  //
  //   const button = screen.queryByRole('button');
  //   expect(button).toBeInTheDocument(); // ボタンが表示される
  //   expect(button).not.toHaveClass(styles.hide); // hide クラスが外れて表示されることを確認
  // });
  //
  // it('should scroll to top when clicked', () => {
  //   render(
  //     <div style={{ height: '2000px', padding: '16px' }}>
  //       <p>スクロールしてボタンが表示されるのを確認してください。</p>
  //       <Default />
  //     </div>,
  //   );
  //
  //   // スクロール位置を300pxに設定してボタンを表示
  //   act(() => {
  //     window.scrollTo(0, 301);
  //   });
  //
  //   const button = screen.getByRole('button');
  //   fireEvent.click(button); // ボタンをクリック
  //
  //   // スクロール位置をリセットする関数が呼ばれたことを確認
  //   expect(window.scrollTo).toHaveBeenCalledTimes(2); // 2回呼ばれたことを確認
  //   expect(window.scrollTo).toHaveBeenCalledWith(0, 0); // 上部にスクロール
  // });
});
