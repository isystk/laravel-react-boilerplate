import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, NoImage, Small } = composeStories(stories);

describe('Avatar Component Tests', () => {
  it('画像URLが渡された場合に画像が表示されること', () => {
    render(<Default />);
    const img = screen.getByRole('img');
    expect(img).toBeInTheDocument();
    expect(img).toHaveAttribute('src', 'https://placehold.co/150x150');
    expect(img).toHaveAttribute('alt', 'Avatar');
  });

  it('画像URLがnullの場合にプレースホルダーが表示されること', () => {
    render(<NoImage />);
    // プレースホルダーのアイコン（👤）が含まれていることを確認
    expect(screen.getByText('👤')).toBeInTheDocument();
    // imgタグが存在しないことを確認
    expect(screen.queryByRole('img')).not.toBeInTheDocument();
  });

  it('サイズが正しく適用されること', () => {
    const { container } = render(<Small />);
    const avatarContainer = container.firstChild as HTMLElement;
    expect(avatarContainer).toHaveStyle({ width: '24px', height: '24px' });
  });

  it('カスタムclassNameが適用されること', () => {
    const customClass = 'test-class';
    render(<Default className={customClass} />);
    const img = screen.getByRole('img');
    // Avatarコンポーネントの構造上、コンテナにclassNameが付与される
    expect(img.parentElement).toHaveClass(customClass);
  });
});
