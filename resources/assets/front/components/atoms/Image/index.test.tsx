import { describe, it, expect, beforeAll } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, WithLazyLoading, WithEagerLoading } = composeStories(stories);

// img タグの loading 属性をモックする
beforeAll(() => {
  Object.defineProperty(HTMLImageElement.prototype, 'loading', {
    set(value) {
      this.setAttribute('loading', value);
    },
    get() {
      return this.getAttribute('loading');
    },
  });
});

describe('Image Storybook Tests', () => {
  it('Default: should render image with correct alt and size', () => {
    render(<Default />);
    const img = screen.getByAltText('サンプル画像') as HTMLImageElement;
    expect(img).toBeInTheDocument();
    expect(img).toHaveAttribute('width', '200');
    expect(img).toHaveAttribute('height', '100');
  });

  it('WithLazyLoading: should render with lazy loading', () => {
    render(<WithLazyLoading />);
    const img = screen.getByAltText('遅延読み込み画像') as HTMLImageElement;
    expect(img.loading).toBe('lazy');
  });

  it('WithEagerLoading: should render with eager loading', () => {
    render(<WithEagerLoading />);
    const img = screen.getByAltText('即時読み込み画像') as HTMLImageElement;
    expect(img.loading).toBe('eager');
  });
});
