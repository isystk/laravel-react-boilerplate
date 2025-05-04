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

  it('should render stock item name and price', () => {
    render(<Default />);
    expect(screen.getByText('テスト商品')).toBeInTheDocument();
    expect(screen.getByText('1500')).toBeInTheDocument();
  });

  it('should render "気になる" button and allow toggle', () => {
    render(<Default />);
    const likeButton = screen.getByText('気になる');
    expect(likeButton).toBeInTheDocument();
    fireEvent.click(likeButton);
    // このあとに API モック等で like 状態が変化するかを検証可能
  });

  it('should handle add to cart button', () => {
    render(<Default />);
    const cartButton = screen.getByText(/カートに入れる/);
    expect(cartButton).toBeInTheDocument();
    fireEvent.click(cartButton);
    // 遷移先URLの検証やAPI呼び出しはモック必要
  });
});
