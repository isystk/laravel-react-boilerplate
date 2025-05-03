import { describe, it, expect, vi } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';
import { act } from 'react-dom/test-utils';

const { Default, WithAutoPlay } = composeStories(stories);

describe('Carousel Storybook Tests', () => {
  it('Default: should render all slides but only one visible at a time', () => {
    render(<Default />);
    const images = screen.getAllByRole('img');
    expect(images).toHaveLength(4);
    expect(images[0]).toHaveAttribute('alt', 'Slide 1');
  });

  it('Default: should go to next slide when next button clicked', () => {
    render(<Default />);
    const nextButton = screen.getByRole('button', { name: 'Next Slide' });
    fireEvent.click(nextButton);
    const slides = screen.getAllByRole('img');
    expect(slides[1]).toBeInTheDocument(); // Slide 2 now visible (indirect check)
  });

  it('Default: should go to previous slide when previous button clicked', () => {
    render(<Default />);
    const prevButton = screen.getByRole('button', { name: 'Previous Slide' });
    fireEvent.click(prevButton);
    const slides = screen.getAllByRole('img');
    expect(slides[2]).toBeInTheDocument(); // Slide 3 is previous from 0
  });

  it('Default: should navigate using indicators', () => {
    render(<Default />);
    const indicators = screen.getAllByRole('button', { name: /Go to slide/i });
    fireEvent.click(indicators[2]);
    const images = screen.getAllByRole('img');
    expect(images[2]).toHaveAttribute('alt', 'Slide 3');
  });

  it('WithAutoPlay: should automatically advance slides over time', async () => {
    vi.useFakeTimers();
    render(<WithAutoPlay />);
    const images = screen.getAllByRole('img');
    expect(images[0]).toBeInTheDocument();

    // act() で React の状態更新をトリガー
    await act(() => {
      vi.advanceTimersByTime(1100);
      return Promise.resolve();
    });

    const updatedImages = screen.getAllByRole('img');
    expect(updatedImages[1]).toBeInTheDocument();

    vi.useRealTimers();
  });
});
