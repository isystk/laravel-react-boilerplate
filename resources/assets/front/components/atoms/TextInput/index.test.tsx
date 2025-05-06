import { describe, it, expect } from 'vitest';
import { fireEvent, render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, WithError, LaravelError } = composeStories(stories);

describe('TextInput Storybook Tests', () => {
  it('テキストボックスが表示されること', () => {
    render(<Default />);
    expect(screen.getByLabelText('メールアドレス')).toBeInTheDocument();
  });

  it('値を変更した際にonChangeイベントが発動すること', () => {
    render(<Default />);
    const input = screen.getByLabelText('メールアドレス') as HTMLInputElement;
    fireEvent.change(input, { target: { value: 'test@example.com' } });
    expect(input.value).toBe('test@example.com');
  });

  it('未入力の場合はエラーメッセージが表示されること', () => {
    render(<WithError />);
    expect(screen.getByText('このフィールドは必須です。')).toBeInTheDocument();
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
