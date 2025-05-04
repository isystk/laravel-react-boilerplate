import { describe, it, expect, afterEach } from 'vitest';
import { render, screen, cleanup } from '@testing-library/react';
import Portal from './index';

describe('Portal component', () => {
  afterEach(() => {
    cleanup();
  });

  it('should render children inside a portal', () => {
    render(
      <Portal>
        <div>テストポータルの中身</div>
      </Portal>,
    );

    expect(screen.getByText('テストポータルの中身')).toBeInTheDocument();
  });

  it('should attach the element to document.body', () => {
    render(
      <Portal>
        <div data-testid="portal-content">内容</div>
      </Portal>,
    );

    const portalContent = document.querySelector('[data-testid="portal-content"]');
    expect(portalContent?.parentElement).toBe(document.body.lastElementChild);
  });
});
