import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, WithError, WithLaravelError } = composeStories(stories);

describe('SelectBox Storybook Tests', () => {
  it('セレクトボックスが表示されること', () => {
    render(<Default />);
    expect(screen.getByLabelText('選択肢')).toBeInTheDocument();
    expect(screen.getByText('オプション1')).toBeInTheDocument();
    expect(screen.getByText('オプション2')).toBeInTheDocument();
    expect(screen.getByText('オプション3')).toBeInTheDocument();
  });

  it('セレクトボックスのオプションを選択すると選択した値がValueに設定されること', () => {
    render(<Default />);
    const select = screen.getByLabelText('選択肢') as HTMLSelectElement;
    fireEvent.change(select, { target: { value: 'option2' } });
    expect(select.value).toBe('option2');
  });

  it('未選択の場合にエラーメッセージが表示されること', () => {
    render(<WithError />);
    expect(screen.getByText('このフィールドは必須です。')).toBeInTheDocument();
  });

  it('未選択の場合にエラーメッセージが表示されること(Laravelのエラー)', () => {
    render(<WithLaravelError />);
    expect(screen.getByText('Laravel側のエラーです')).toBeInTheDocument();
  });

  it('表示された後、グローバル変数からは削除されること', () => {
    render(<WithLaravelError />);
    expect(window.laravelErrors.message).toBeUndefined();
  });
});
