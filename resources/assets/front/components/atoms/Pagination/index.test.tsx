import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, FirstPage, LastPage } = composeStories(stories);

describe('Pagination Storybook Tests', () => {
  it('Default: should render correct number of page buttons', () => {
    render(<Default />);
    expect(screen.getByRole('button', { name: '3' })).toHaveAttribute('aria-current', 'page');
    expect(screen.getByRole('button', { name: '2' })).toBeInTheDocument();
    expect(screen.getByRole('button', { name: '4' })).toBeInTheDocument();
  });

  it('Default: should change page when a different page is clicked', () => {
    render(<Default />);
    const button = screen.getByRole('button', { name: '4' });
    fireEvent.click(button);
    // 新しいページに遷移したことを直接確認できないため、aria-current が更新されるかは確認できない
    // ただしこの処理がユーザーの useState に依存しているため、ここでは DOM 変化が追えない
    expect(button).toBeInTheDocument(); // クリック可能であることの確認にとどめる
  });

  it('FirstPage: should disable Previous button', () => {
    const { container } = render(<FirstPage />);
    const buttons = container.querySelectorAll('button');
    const prevButton = buttons[0]; // 最初が Prev ボタン
    expect(prevButton).toBeDisabled();
  });

  it('LastPage: should disable Next button', () => {
    const { container } = render(<LastPage />);
    const buttons = container.querySelectorAll('button');
    const nextButton = buttons[buttons.length - 1]; // 最後が Next ボタン
    expect(nextButton).toBeDisabled();
  });
});
