import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { WithLink, WithoutLink } = composeStories(stories);

describe('Logo Storybook Tests', () => {
  it('WithLink: should render logo inside a link', () => {
    render(<WithLink />);
    const logoImage = screen.getByRole('img');
    expect(logoImage).toBeInTheDocument();
    expect(logoImage.closest('a')).toHaveAttribute('href', '/'); // Url.top assumed as '/'
  });

  it('WithoutLink: should render logo without link', () => {
    render(<WithoutLink />);
    const logoImage = screen.getByRole('img');
    expect(logoImage).toBeInTheDocument();
    expect(logoImage.closest('a')).toBeNull();
  });
});
