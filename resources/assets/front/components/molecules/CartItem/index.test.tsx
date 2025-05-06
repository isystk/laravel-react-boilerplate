import { render, screen, fireEvent } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('CartItem Storybook Tests', () => {
  it('カートアイテムが表示されること', () => {
    render(<Default />);
    expect(screen.getByText('テスト商品')).toBeInTheDocument();
    expect(screen.getByText('1000')).toBeInTheDocument();
  });

  it('カートから削除するボタンをクリックすると確認ダイアログが表示されること', () => {
    render(<Default />);
    const deleteButton = screen.getByText('カートから削除する');
    fireEvent.click(deleteButton);
    expect(screen.getByText('削除します。よろしいですか？')).toBeInTheDocument();
  });
});
