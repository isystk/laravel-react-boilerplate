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

  it('should render modal title and amount', () => {
    render(<Default />);
    expect(screen.getByText('決済情報の入力')).toBeInTheDocument();
    expect(screen.getByText('3000円')).toBeInTheDocument();
  });

  // it('should show validation errors when submitting', async () => {
  //   render(<Default />);
  //   const submitButton = screen.getByText('購入する');
  //   fireEvent.click(submitButton);
  //
  //   // queryByText を使用してエラーメッセージが現れるのを待つ
  //   await waitFor(() => {
  //     expect(screen.queryByText('カード番号を入力してください')).toBeInTheDocument();
  //     expect(screen.queryByText('有効期限を入力してください')).toBeInTheDocument();
  //     expect(screen.queryByText('セキュリティコードを入力してください')).toBeInTheDocument();
  //   });
  // });
});
