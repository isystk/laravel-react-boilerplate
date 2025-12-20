import { describe, it, expect, vi } from 'vitest';
import { render, screen } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';
import styles from './styles.module.scss';

const { WithMessage, LaravelSessionMessage, ErrorMessage } = composeStories(stories);

describe('FlashMessage Storybook Tests', () => {
  it('フラッシュメッセージが表示されること', () => {
    render(<WithMessage />);
    expect(screen.getByText('これはフラッシュメッセージです。')).toBeInTheDocument();
  });

  it('フラッシュメッセージが表示されること(Laravelのメッセージ)', () => {
    render(<LaravelSessionMessage />);
    expect(screen.getByText('Laravel側のメッセージです')).toBeInTheDocument();
  });

  it('表示された後、グローバル変数からは削除されること', () => {
    render(<LaravelSessionMessage />);
    expect(window.laravelSession.status).toBeUndefined();
  });

  it('5秒後にフラッシュメッセージが消えること', () => {
    vi.useFakeTimers();
    render(<WithMessage />);
    const message = screen.getByText('これはフラッシュメッセージです。');
    expect(message).toBeInTheDocument();
    vi.advanceTimersByTime(5000);
    vi.useRealTimers();
  });

  it('エラーメッセージが表示されること', () => {
    render(<ErrorMessage />);
    const message = screen.getByText('これはエラーメッセージです。');
    expect(message).toBeInTheDocument();
    expect(message).toHaveClass(styles.error);
  });
});
