import { render, screen, fireEvent } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('CartItem Storybook Tests', () => {
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
});
