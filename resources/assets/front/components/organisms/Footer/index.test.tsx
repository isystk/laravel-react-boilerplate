import { render, screen } from '@testing-library/react';
import { describe, it, vi, expect, beforeEach } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('Footer Storybook Tests', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('should render footer content', () => {
    render(<Default />);
    // ロゴが表示されていることをテスト
    expect(screen.getByAltText('LaraEC')).toBeInTheDocument();
    // フッターコピーが表示されていることをテスト
    expect(screen.getByText(/このページは架空のページです/)).toBeInTheDocument();
  });
});
