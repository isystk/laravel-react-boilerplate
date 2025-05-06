import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default, WithError, WithPreview } = composeStories(stories);

describe('ImageInput Storybook Tests', () => {
  it('画像ファイル選択が表示されること', async () => {
    render(<Default />);
    const input = screen.getByLabelText('プロフィール画像');
    expect(input).toBeInTheDocument();
  });

  it('画像ファイルが未選択の場合にエラーメッセージが表示されること', () => {
    render(<WithError />);
    expect(screen.getByText('画像は必須です')).toBeInTheDocument();
  });

  it('画像ファイルを選択するとプレビューが表示されること', async () => {
    render(<WithPreview />);

    const input = screen.getByLabelText('プロフィール画像');

    const file = new File(['image'], 'profile.png', { type: 'image/png' });
    fireEvent.change(input, { target: { files: [file] } });

    // プレビュー画像が表示されることを確認
    const img = await screen.findByAltText('プレビュー');
    expect(img).toBeInTheDocument();
  });
});
