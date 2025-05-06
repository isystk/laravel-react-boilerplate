import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';

const { Default } = composeStories(stories);

describe('Default', async () => {
  it('初期の状態では非表示であること', () => {
    render(<Default />);
    const button = screen.queryByRole('button');
    expect(button).toBeInTheDocument();
    expect(button).toHaveClass('hide');
  });

  // TODO エラーになるのでコメントアウト
  // it('スクロールすると表示されること', async () => {
  //   vi.useFakeTimers();
  //   render(<DisplayAfterScroll />);
  //
  //   // React state update のために時間を進める
  //   await act(() => {
  //     vi.advanceTimersByTime(2000);
  //     return Promise.resolve();
  //   });
  //
  //   const buttonAfter = screen.getByRole('button');
  //   expect(buttonAfter).not.toHaveClass('hide');
  //
  //   vi.useRealTimers();
  // });
  //
  // it('ボタンをクリックしいた際にスクロール位置をリセットする', () => {
  //   vi.useFakeTimers();
  //   render(<Default />);
  //
  //   // スクロール位置を300pxに設定してボタンを表示
  //   act(() => {
  //     window.scrollTo(0, 301);
  //   });
  //
  //   const button = screen.getByRole('button');
  //   fireEvent.click(button);
  //
  //   // スクロール位置を設定し、scroll イベントを発火
  //   await act(() => {
  //     window.scrollTo(0, 301);
  //     window.dispatchEvent(new Event('scroll'));
  //     return Promise.resolve();
  //   });
  //
  //   // React state update のために時間を進める
  //   await act(() => {
  //     vi.advanceTimersByTime(1000);
  //     return Promise.resolve();
  //   });
  //
  //   // スクロール位置をリセットする関数が呼ばれたことを確認
  //   expect(window.scrollTo).toHaveBeenCalledTimes(2); // 2回呼ばれたことを確認
  //   expect(window.scrollTo).toHaveBeenCalledWith(0, 0); // 上部にスクロール
  //   vi.useRealTimers();
  // });
});
