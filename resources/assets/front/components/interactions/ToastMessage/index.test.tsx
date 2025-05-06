import { describe, it, expect, vi } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Alert, Confirm } = composeStories(stories);

describe('ToastMessage Storybook Tests', () => {
  it('アラートが表示されること', () => {
    render(<Alert />);
    expect(screen.getByText('アラートメッセージです。')).toBeInTheDocument();
    expect(screen.getByText('はい')).toBeInTheDocument();
  });

  it('アラートの「はい」をクリックするとonConfirmイベントが発動すること', () => {
    window.alert = vi.fn();
    render(<Alert />);
    fireEvent.click(screen.getByText('はい'));
    expect(window.alert).toHaveBeenCalledWith('確認されました');
  });

  it('確認ダイアログが表示されること', () => {
    render(<Confirm />);
    expect(screen.getByText('この操作を実行しますか？')).toBeInTheDocument();
    expect(screen.getByText('はい')).toBeInTheDocument();
    expect(screen.getByText('いいえ')).toBeInTheDocument();
  });

  it('確認ダイアログの「はい」をクリックするとonConfirmイベントが発動すること', () => {
    window.alert = vi.fn();
    render(<Confirm />);
    fireEvent.click(screen.getByText('はい'));
    expect(window.alert).toHaveBeenCalledWith('はいが選択されました');
  });

  it('確認ダイアログの「いいえ」をクリックするとonCancelイベントが発動すること', () => {
    window.alert = vi.fn();
    render(<Confirm />);
    fireEvent.click(screen.getByText('いいえ'));
    expect(window.alert).toHaveBeenCalledWith('いいえが選択されました');
  });
});
