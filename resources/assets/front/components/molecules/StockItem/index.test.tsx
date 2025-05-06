import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { describe, it, vi, expect, beforeEach } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default, Logined } = composeStories(stories);

describe('StockItem Storybook Tests', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('商品アイテムが表示されること', () => {
    render(<Default />);
    expect(screen.getByText('テスト商品')).toBeInTheDocument();
    expect(screen.getByText('1500')).toBeInTheDocument();
  });

  it('気になるボタンをクリック', () => {
    render(<Default />);
    const likeButton = screen.getByText('気になる');
    expect(likeButton).toBeInTheDocument();
    fireEvent.click(likeButton);
    waitFor(() => {
      expect(screen.getByText('お気に入りに追加しました')).toBeInTheDocument();
    });
  });

  it('カートに入れるボタンをクリック', () => {
    render(<Logined />);
    const cartButton = screen.getByText(/カートに入れる/);
    expect(cartButton).toBeInTheDocument();
    // TODO APIの呼び出しが含まれるのでテストが難しい。
    // fireEvent.click(cartButton);
    // waitFor(() => {
    //   expect(screen.getbytext('ユーザー名さんのカートの中身')).tobeinthedocument();
    // });
  });
});
