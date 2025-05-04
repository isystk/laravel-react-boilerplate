import { describe, it, expect, vi } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Alert, Confirm } = composeStories(stories);

describe('ToastMessage Storybook Tests', () => {
  it('Alert: should render alert message and confirm button', () => {
    render(<Alert />);
    expect(screen.getByText('アラートメッセージです。')).toBeInTheDocument();
    expect(screen.getByText('はい')).toBeInTheDocument();
  });

  it('Alert: should call onConfirm when confirm button is clicked', () => {
    window.alert = vi.fn(); // モック alert
    render(<Alert />);
    fireEvent.click(screen.getByText('はい'));
    expect(window.alert).toHaveBeenCalledWith('確認されました');
  });

  it('Confirm: should render confirm and cancel buttons', () => {
    render(<Confirm />);
    expect(screen.getByText('この操作を実行しますか？')).toBeInTheDocument();
    expect(screen.getByText('はい')).toBeInTheDocument();
    expect(screen.getByText('いいえ')).toBeInTheDocument();
  });

  it('Confirm: should call onConfirm when "はい" is clicked', () => {
    window.alert = vi.fn();
    render(<Confirm />);
    fireEvent.click(screen.getByText('はい'));
    expect(window.alert).toHaveBeenCalledWith('はいが選択されました');
  });

  it('Confirm: should call onCancel when "いいえ" is clicked', () => {
    window.alert = vi.fn();
    render(<Confirm />);
    fireEvent.click(screen.getByText('いいえ'));
    expect(window.alert).toHaveBeenCalledWith('いいえが選択されました');
  });
});
