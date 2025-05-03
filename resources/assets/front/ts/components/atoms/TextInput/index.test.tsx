import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import * as stories from './index.stories'; // ← Story ファイルのパスに合わせて調整
import '@testing-library/jest-dom';
import { composeStories } from '@storybook/react';

const { Default, WithError, LaravelErrorSimulated } = composeStories(stories);

describe('TextInput Storybook Tests', () => {
  it('Default: should render input with label', () => {
    render(<Default />);
    expect(screen.getByLabelText('メールアドレス')).toBeInTheDocument();
  });

  it('Default: should call onChange when typing', () => {
    render(<Default />);
    const input = screen.getByLabelText('メールアドレス') as HTMLInputElement;
    fireEvent.change(input, { target: { value: 'test@example.com' } });
    expect(input.value).toBe('test@example.com');
  });

  it('WithError: should display error message', () => {
    render(<WithError />);
    expect(screen.getByText('このフィールドは必須です。')).toBeInTheDocument();
  });

  it('LaravelErrorSimulated: should display Laravel error message from window object', () => {
    // Laravel のエラーを window に模擬的に注入
    window.laravelErrors = {
      email: ['Laravelからのエラーメッセージです。'],
    };
    render(<LaravelErrorSimulated />);
    expect(screen.getByText('Laravelからのエラーメッセージです。')).toBeInTheDocument();
  });
});
