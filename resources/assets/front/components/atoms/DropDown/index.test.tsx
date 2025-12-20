import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default } = composeStories(stories);

describe('DropDown Storybook Tests', () => {
  it('トグルボタンが表示されメニューが非表示であること', () => {
    render(<Default />);
    const toggle = screen.getByRole('button', { name: 'メニューを開く' });
    expect(toggle).toBeInTheDocument();
    const dropdownMenu = document.querySelector('.dropdownMenu');
    expect(dropdownMenu).toHaveClass('hidden');
  });

  it('トグルボタンをクリックするとメニューが表示されること', () => {
    render(<Default />);
    const toggle = screen.getByRole('button', { name: 'メニューを開く' });
    fireEvent.click(toggle);
    expect(screen.getByText('選択肢1')).toBeInTheDocument();
    expect(screen.getByText('選択肢2')).toBeInTheDocument();
    expect(screen.getByText('選択肢3')).toBeInTheDocument();
    const dropdownMenu = document.querySelector('.dropdownMenu');
    expect(dropdownMenu).not.toHaveClass('hidden');
  });

  it('メニューのアイテムをクリックした際にその処理が実行されること', () => {
    render(<Default />);
    const item = screen.getByText('選択肢1');
    fireEvent.click(item);
    expect(screen.getByText('選択された項目: 選択肢1')).toBeInTheDocument();
  });
});
