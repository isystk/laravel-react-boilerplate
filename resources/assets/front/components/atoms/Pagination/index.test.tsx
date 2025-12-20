import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, FirstPage, LastPage } = composeStories(stories);

describe('Pagination Storybook Tests', () => {
  it('ページングボタンが表示されること', () => {
    render(<Default />);
    expect(screen.getByRole('button', { name: '3' })).toHaveAttribute('aria-current', 'page');
    expect(screen.getByRole('button', { name: '2' })).toBeInTheDocument();
    expect(screen.getByRole('button', { name: '4' })).toBeInTheDocument();
  });

  it('ページングボタンをクリックするとアクティブなページが切り替わること', () => {
    render(<Default />);
    expect(screen.getByRole('button', { name: '3' })).toHaveAttribute('aria-current', 'page');
    const button = screen.getByRole('button', { name: '4' });
    fireEvent.click(button);
    expect(screen.getByRole('button', { name: '4' })).toHaveAttribute('aria-current', 'page');
  });

  it('1ページ目が表示されている場合は前ページボタンが非活性になること', () => {
    const { container } = render(<FirstPage />);
    const buttons = container.querySelectorAll('button');
    const prevButton = buttons[0];
    expect(prevButton).toBeDisabled();
  });

  it('最終ページが表示されている場合は次ページボタンが非活性になること', () => {
    const { container } = render(<LastPage />);
    const buttons = container.querySelectorAll('button');
    const nextButton = buttons[buttons.length - 1]; // 最後が Next ボタン
    expect(nextButton).toBeDisabled();
  });
});
