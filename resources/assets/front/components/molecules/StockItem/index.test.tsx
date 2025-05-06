import { render, screen, fireEvent } from '@testing-library/react';
import { describe, it, vi, expect, beforeEach } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

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
    // TODO このあとに API モック等で like 状態が変化するかを検証可能
  });

  it('カートに入れるボタンをクリック', () => {
    render(<Default />);
    const cartButton = screen.getByText(/カートに入れる/);
    expect(cartButton).toBeInTheDocument();
    fireEvent.click(cartButton);
    // TODO 遷移先URLの検証やAPI呼び出しはモック必要
  });
});
