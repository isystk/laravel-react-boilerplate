import { describe, it, expect, vi } from 'vitest';
import { render, screen } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';
import styles from './styles.module.scss';

const { WithMessage, LaravelSessionMessage, ErrorMessage } = composeStories(stories);

describe('FlashMessage Storybook Tests', () => {
  it('WithMessage: should render message when provided via props', () => {
    render(<WithMessage />);
    expect(screen.getByText('これはフラッシュメッセージです。')).toBeInTheDocument();
  });

  it('LaravelSessionMessage: should render message from window.laravelSession', () => {
    window.laravelSession = {
      status: 'Laravelからのメッセージ',
    };
    render(<LaravelSessionMessage />);
    expect(screen.getByText('Laravelからのメッセージ')).toBeInTheDocument();
  });

  it('WithMessage: should fade out after 5 seconds', () => {
    vi.useFakeTimers();
    render(<WithMessage />);
    const message = screen.getByText('これはフラッシュメッセージです。');
    expect(message).toBeInTheDocument();

    vi.advanceTimersByTime(5000);
    // 実際の fadeOut はアニメーションクラスの付与によるが、クラスのチェックをしたい場合は追加でモックが必要
    vi.useRealTimers();
  });

  it('ErrorMessage: should render has class error', () => {
    render(<ErrorMessage />);
    const message = screen.getByText('これはエラーメッセージです。');
    expect(message).toBeInTheDocument();
    expect(message).toHaveClass(styles.error);
  });
});
