import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { describe, it, vi, expect, beforeEach } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default, Login } = composeStories(stories);

describe('Header Storybook Tests', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('ログイン前', () => {
    render(<Default />);
    const userTexts = screen.queryAllByText(/様$/);
    const loginButtons = screen.queryAllByText('ログイン');
    expect(0 === userTexts.length, 'ログインユーザーの名前が表示されない').toBe(true);
    expect(0 < loginButtons.length, 'ログインボタンが表示される').toBe(true);
  });

  it('ログイン後', async () => {
    render(<Login />);
    await waitFor(() => {
      const userTexts = screen.queryAllByText(/様$/);
      const loginButtons = screen.queryAllByText('ログイン');
      expect(0 < userTexts.length, 'ログインユーザーの名前が表示される').toBe(true);
      expect(0 === loginButtons.length, 'ログインボタンが消える').toBe(true);
    });
  });

  it('お問い合わせボタンのクリックイベント', () => {
    render(<Default />);
    const contactLink = screen.getAllByText('お問い合わせ')[0];
    expect(contactLink).toBeInTheDocument();
    fireEvent.click(contactLink); // クリックしてもモーダルやページ遷移しないが、イベント検知は可
  });
});
