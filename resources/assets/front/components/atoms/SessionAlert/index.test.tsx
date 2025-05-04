import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import SessionAlert from './index';

const { DefaultMessage, ResentMessage, NoMessage } = composeStories(stories);

describe('SessionAlert', () => {
  it('DefaultMessage: should display session message from window', () => {
    render(<DefaultMessage />);
    expect(screen.getByText('登録が完了しました')).toBeInTheDocument();
  });

  it('ResentMessage: should show static message regardless of session value', () => {
    render(<ResentMessage />);
    expect(
      screen.getByText('あなたのメールアドレスに新しい認証リンクが送信されました。'),
    ).toBeInTheDocument();
  });

  it('NoMessage: should render nothing if no session value exists', () => {
    const { container } = render(<NoMessage />);
    expect(container).toBeEmptyDOMElement();
  });

  it('should clear session message after rendering', () => {
    window.laravelSession = { success: '一度表示されるメッセージ' };
    render(<SessionAlert target="success" />);
    expect(window.laravelSession.success).toBe('');
  });
});
