import { describe, it, expect } from 'vitest';
import { render, screen, waitFor } from '@testing-library/react';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';

const { LoggedInVerified, NotLoggedIn, NotVerified } = composeStories(stories);

describe('AuthCheck Storybook Tests', () => {
  it('ログイン済み且つメール認証済みの場合 -> プロテクトされたコンテンツが表示される', () => {
    render(<LoggedInVerified />);
    expect(screen.getByText('Protected Content')).toBeInTheDocument();
  });

  it('未ログインの場合 -> ログイン画面にリダイレクトされる', () => {
    render(<NotLoggedIn />);
    expect(screen.queryByText('Should not render')).not.toBeInTheDocument();
  });

  it('ログイン済み且つメール認証がされていない場合 -> ログイン画面にリダイレクトされる', () => {
    render(<NotVerified />);
    waitFor(() => {
      expect(
        screen.queryByText('確認用リンクが記載されたメールをご確認ください。'),
      ).toBeInTheDocument();
    });
  });
});
