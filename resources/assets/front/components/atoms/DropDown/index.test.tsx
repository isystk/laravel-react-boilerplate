import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default, NoActions } = composeStories(stories);

describe('DropDown Storybook Tests', () => {
  it('Default: should render toggle button', () => {
    render(<Default />);
    expect(screen.getByRole('button', { name: 'メニューを開く' })).toBeInTheDocument();
  });

  it('Default: should show dropdown items when clicked', () => {
    render(<Default />);
    const toggle = screen.getByRole('button', { name: 'メニューを開く' });
    fireEvent.click(toggle);
    expect(screen.getByText('選択肢1')).toBeInTheDocument();
    expect(screen.getByText('選択肢2')).toBeInTheDocument();
    expect(screen.getByText('選択肢3')).toBeInTheDocument();
  });

  it('Default: should call onClick and close menu on item click', () => {
    render(<Default />);
    fireEvent.click(screen.getByRole('button', { name: 'メニューを開く' }));
    const item = screen.getByText('選択肢1');
    fireEvent.click(item);
    expect(screen.getByText('選択された項目: 選択肢1')).toBeInTheDocument();
  });

  it('NoActions: should render items without crashing', () => {
    render(<NoActions />);
    fireEvent.click(screen.getByRole('button', { name: 'クリックして開く' }));
    expect(screen.getByText('アクションなし1')).toBeInTheDocument();
    expect(screen.getByText('アクションなし2')).toBeInTheDocument();
  });
});
