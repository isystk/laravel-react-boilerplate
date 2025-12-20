import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default } = composeStories(stories);

describe('Portal Storybook Tests', () => {
  it('ポータル内の要素が表示されること', () => {
    render(<Default />);
    expect(screen.getByText('Portal内に描画されたコンテンツ')).toBeInTheDocument();
  });

  it('ポータル内の要素が document.body に追加されていること', () => {
    render(<Default />);
    const portalContent = document.querySelector('[data-testid="portal-content"]');
    expect(portalContent?.parentElement).toBe(document.body.lastElementChild);
  });
});
