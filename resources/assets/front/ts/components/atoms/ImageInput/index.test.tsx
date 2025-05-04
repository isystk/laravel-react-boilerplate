import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default, WithError, WithPreview } = composeStories(stories);

describe('ImageInput Storybook Tests', () => {
  it('Default: should render input and allow file selection', async () => {
    render(<Default />);
    const input = screen.getByLabelText('プロフィール画像');
    expect(input).toBeInTheDocument();

    // モックファイルを作成
    const file = new File(['image'], 'profile.png', { type: 'image/png' });
    fireEvent.change(input, { target: { files: [file] } });

    // ファイルが読み込まれ、プレビューが表示されることを確認
    const img = await screen.findByAltText('プレビュー');
    expect(img).toBeInTheDocument();
  });

  it('WithError: should display error message', () => {
    render(<WithError />);
    expect(screen.getByText('画像は必須です')).toBeInTheDocument();
  });

  it('WithPreview: should display preview when an image is selected', async () => {
    render(<WithPreview />);

    const input = screen.getByLabelText('プロフィール画像');

    const file = new File(['image'], 'profile.png', { type: 'image/png' });
    fireEvent.change(input, { target: { files: [file] } });

    // プレビュー画像が表示されることを確認
    const img = await screen.findByAltText('プレビュー');
    expect(img).toBeInTheDocument();
  });
});
