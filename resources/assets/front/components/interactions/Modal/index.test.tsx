import { describe, it, expect } from 'vitest';
import { render, fireEvent, screen, waitFor } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default, Small } = composeStories(stories);

describe('Modal Storybook Tests', () => {
  it('モーダルが表示されること', () => {
    render(<Default />);
    fireEvent.click(screen.getByText('Open Modal'));
    expect(screen.getByText('モーダルの中身')).toBeInTheDocument();
  });

  it('モーダルが表示されること(サイズ小)', () => {
    render(<Small />);
    fireEvent.click(screen.getByText('Open Small Modal'));
    expect(screen.getByText('小さなモーダル')).toBeInTheDocument();
  });

  it('オーバーレイをクリックするとモーダルが閉じること', async () => {
    render(<Default />);

    fireEvent.click(screen.getByText('Open Modal'));
    expect(screen.getByText('モーダルの中身')).toBeInTheDocument();

    // overlay 要素を取得してクリック
    const overlay = document.querySelector('[class*="overlay"]') as HTMLElement;
    fireEvent.click(overlay);

    // モーダルの DOM が消えるまで待つ
    await waitFor(() => {
      expect(screen.queryByText('モーダルの中身')).not.toBeInTheDocument();
    });
  });
});
