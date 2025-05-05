import { describe, it, expect, vi } from 'vitest';
import { render, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';
import '@testing-library/jest-dom';

const { Default } = composeStories(stories);

describe('HamburgerButton', () => {
  it('should render three span elements', () => {
    const { container } = render(<Default onClick={vi.fn()} />);
    const spans = container.querySelectorAll('span');
    expect(spans.length).toBe(3);
  });

  it('should toggle open state on click', () => {
    const handleClick = vi.fn();
    const { container } = render(<Default onClick={handleClick} />);
    const button = container.querySelector('div');

    if (button) {
      fireEvent.click(button);
      expect(handleClick).toHaveBeenCalledWith(true);

      fireEvent.click(button);
      expect(handleClick).toHaveBeenCalledWith(false);
    } else {
      throw new Error('Hamburger div not found');
    }
  });
});
