import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, WithError, LaravelError } = composeStories(stories);

describe('TextArea', () => {
  it('テキストエリアが表示されること', () => {
    render(<Default />);
    expect(screen.getByLabelText('お問い合わせ内容')).toHaveValue('初期値です');
  });

  it('未入力の場合はエラーメッセージが表示されること', () => {
    render(<WithError />);
    expect(screen.getByText('必須項目です')).toBeInTheDocument();
  });

  it('未入力の場合にエラーメッセージが表示されること(Laravelのエラー)', () => {
    render(<LaravelError />);
    expect(screen.getByText('Laravel側のエラーです')).toBeInTheDocument();
  });

  it('表示された後、グローバル変数からは削除されること', () => {
    render(<LaravelError />);
    expect(window.laravelErrors.message).toBeUndefined();
  });
});
