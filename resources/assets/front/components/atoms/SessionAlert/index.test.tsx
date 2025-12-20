import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import SessionAlert from './index';

const { DefaultMessage, ResentMessage, NoMessage } = composeStories(stories);

describe('SessionAlert Storybook Tests', () => {
  it('セッションアラートのメッセージが表示されること', () => {
    render(<DefaultMessage />);
    expect(screen.getByText('登録が完了しました')).toBeInTheDocument();
  });

  it('認証リンク送信のメッセージが表示されること', () => {
    render(<ResentMessage />);
    expect(
      screen.getByText('あなたのメールアドレスに新しい認証リンクが送信されました。'),
    ).toBeInTheDocument();
  });

  it('セッションアラートがない場合は表示されないこと', () => {
    const { container } = render(<NoMessage />);
    expect(container).toBeEmptyDOMElement();
  });

  it('表示された後、グローバル変数からは削除されること', () => {
    window.laravelSession = { success: '一度表示されるメッセージ' };
    render(<SessionAlert target="success" />);
    expect(window.laravelSession.success).toBe('');
  });
});
