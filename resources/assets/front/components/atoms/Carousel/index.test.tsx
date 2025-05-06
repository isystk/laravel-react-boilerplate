import { describe, it, expect, vi } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';
import { act } from 'react-dom/test-utils';

const { Default, WithAutoPlay } = composeStories(stories);

describe('Carousel Storybook Tests', () => {
  it('カルーセルに4つの画像が設定されていて1番目の画像が表示されていること', () => {
    render(<Default />);
    const images = screen.getAllByRole('img');
    expect(images).toHaveLength(4);
    const parent = images[0].parentElement;
    expect(parent).toHaveClass('active');
  });

  it('次ページボタンをクリックすると2ページ目が表示されること', () => {
    render(<Default />);
    const nextButton = screen.getByRole('button', { name: 'Next Slide' });
    fireEvent.click(nextButton);
    const images = screen.getAllByRole('img');
    const parent = images[1].parentElement;
    expect(parent).toHaveClass('active');
  });

  it('前ページボタンをクリックすると4ページ目が表示されること', () => {
    render(<Default />);
    const prevButton = screen.getByRole('button', { name: 'Previous Slide' });
    fireEvent.click(prevButton);
    const images = screen.getAllByRole('img');
    const parent = images[3].parentElement;
    expect(parent).toHaveClass('active');
  });

  it('ナビゲーションの3番目をクリックすると3ページ目が表示されること', () => {
    render(<Default />);
    const indicators = screen.getAllByRole('button', { name: /Go to slide/i });
    fireEvent.click(indicators[2]);
    const images = screen.getAllByRole('img');
    const parent = images[2].parentElement;
    expect(parent).toHaveClass('active');
  });

  it('1秒後に自動で次ページにスライドされること', async () => {
    vi.useFakeTimers();
    render(<WithAutoPlay />);
    const images = screen.getAllByRole('img');
    const parent = images[0].parentElement;
    expect(parent).toHaveClass('active');

    // act() で React の状態更新をトリガー
    await act(() => {
      vi.advanceTimersByTime(1100);
      return Promise.resolve();
    });

    const updatedImages = screen.getAllByRole('img');
    const updatedParent = updatedImages[1].parentElement;
    expect(updatedParent).toHaveClass('active');
    expect(parent).not.toHaveClass('active');

    vi.useRealTimers();
  });
});
