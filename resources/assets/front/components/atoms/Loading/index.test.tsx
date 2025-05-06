import { describe, it, expect, vi } from 'vitest';
import { render } from '@testing-library/react';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import { act } from 'react-dom/test-utils'; // Storybookファイル名に合わせて変更してください

const { Default, HideLoading } = composeStories(stories);

describe('Loading component (Storybook)', () => {
  it('ローディング表示がされること', () => {
    render(<Default />);
    expect(document.querySelector('#loading')).toBeInTheDocument();
  });

  it('1秒後にローディング表示が消えること', async () => {
    vi.useFakeTimers();
    render(<HideLoading />);

    await act(() => {
      vi.advanceTimersByTime(1100);
      return Promise.resolve();
    });

    expect(document.querySelector('#loading')).not.toBeInTheDocument();
  });
});
