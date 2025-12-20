import { describe, it, expect } from 'vitest';
import { render, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('HamburgerButton', () => {
  it('ハンバーガーメニューが表示されること', () => {
    const { container } = render(<Default />);
    const spans = container.querySelectorAll('span');
    expect(spans.length).toBe(3);
    const button = container.querySelector('div');
    expect(button).not.toHaveClass('open');
  });

  it('クリックするとバツボタンに変わること', () => {
    const { container } = render(<Default />);
    const button = container.querySelector('div');
    fireEvent.click(button!);
    expect(button).toHaveClass('open');
  });
});
