import { render, screen } from '@testing-library/react';
import { describe, it, vi, expect, beforeEach } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('PaymentModal Storybook Tests', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('決済用のモーダルが表示されること', () => {
    render(<Default />);
    expect(screen.getByText('決済情報の入力')).toBeInTheDocument();
    expect(screen.getByText('3000円')).toBeInTheDocument();
  });

  // it('カード情報を未入力で購入するボタンをクリックするとエラーメッセージが表示されること', async () => {
  //   vi.useFakeTimers();
  //   render(<Default />);
  //
  //   const submitButton = screen.getByText('購入する');
  //   fireEvent.click(submitButton);
  //
  //   // React state update のために時間を進める
  //   await act(() => {
  //     vi.advanceTimersByTime(1000);
  //     return Promise.resolve();
  //   });
  //
  //   expect(screen.getByText('カード番号を入力してください')).toBeInTheDocument();
  //   expect(screen.getByText('有効期限を入力してください')).toBeInTheDocument();
  //   expect(screen.getByText('セキュリティコードを入力してください')).toBeInTheDocument();
  //
  //   vi.useRealTimers();
  // });
});
