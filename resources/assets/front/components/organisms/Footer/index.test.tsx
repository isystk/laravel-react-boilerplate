import { render, screen } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('Footer Storybook Tests', () => {
  it('フッターが表示されること', () => {
    render(<Default />);
    // ロゴが表示されていることをテスト
    expect(screen.getByAltText('LaraEC')).toBeInTheDocument();
    // フッターコピーが表示されていることをテスト
    expect(screen.getByText(/このページは架空のページです/)).toBeInTheDocument();
  });
});
