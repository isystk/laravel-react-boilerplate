import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default } = composeStories(stories);

describe('Circles Storybook Tests', () => {
  it('Circles が20個表示されていること', () => {
    render(<Default />);
    const circles = screen.getByRole('list');
    expect(circles).toBeInTheDocument();
    expect(circles.children.length).toBe(20);
    expect(screen.getByText('Some content inside Circles component')).toBeInTheDocument();
  });
});
