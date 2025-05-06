import { render, screen } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import * as stories from './index.stories';
import { composeStories } from '@storybook/react';

const { Default500, NotFound404 } = composeStories(stories);

describe('ErrorPage', () => {
  it('500 エラーページが表示されること', () => {
    render(<Default500 />);
    expect(screen.getByText(/500 - サーバーエラーが発生しました/)).toBeInTheDocument();
    expect(screen.getByText(/少し時間を置いて再度お試しください。/)).toBeInTheDocument();
  });

  it('404 エラーページが表示されること', () => {
    render(<NotFound404 />);
    expect(screen.getByText(/404 - ページが見つかりません/)).toBeInTheDocument();
    expect(screen.getByText(/お探しのページは存在しないか、移動されました。/)).toBeInTheDocument();
  });

  it('ホームに戻るリンクをクリックすうるとTop画面に遷移すること', () => {
    render(<Default500 />);
    const link = screen.getByRole('link', { name: 'ホームに戻る' });
    expect(link).toHaveAttribute('href', '/'); // Url.top が '/' の想定
  });
});
