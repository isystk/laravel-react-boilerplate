import { render, screen, fireEvent } from '@testing-library/react';
import { describe, it, vi, expect, beforeEach } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('CartItem Storybook Tests', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('should render product name and price', () => {
    render(<Default />);
    expect(screen.getByText('テスト商品')).toBeInTheDocument();
    expect(screen.getByText('1000')).toBeInTheDocument();
  });

  it('should open confirm toast on delete button click', () => {
    render(<Default />);
    const deleteButton = screen.getByText('カートから削除する');
    fireEvent.click(deleteButton);
    expect(screen.getByText('削除します。よろしいですか？')).toBeInTheDocument();
  });

  it('should remove item on confirm', async () => {
    render(<Default />);
    fireEvent.click(screen.getByText('カートから削除する'));

    const confirmButton = screen.getByText('はい');
    fireEvent.click(confirmButton);

    expect(await screen.queryByText('削除します。よろしいですか？')).not.toBeInTheDocument();
  });
});
