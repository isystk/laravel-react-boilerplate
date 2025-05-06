import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { CheckboxDefault, RadioDefault, WithError, WithLaravelError } = composeStories(stories);

describe('SelectionInput Storybook Tests', () => {
  it('チェックボックスが表示されて選択肢Cが選択済みであること', () => {
    render(<CheckboxDefault />);
    expect(screen.getByRole('group', { name: 'チェックボックス' })).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢A')).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢B')).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢C')).toBeInTheDocument();
    const checkB = screen.getByDisplayValue('b') as HTMLInputElement;
    expect(checkB.checked).toBe(true);
  });

  it('ラジオボタンが表示されて選択肢Cが選択済みであること', () => {
    render(<RadioDefault />);
    expect(screen.getByRole('group', { name: 'ラジオボタン' })).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢A')).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢B')).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢C')).toBeInTheDocument();
    const radioC = screen.getByDisplayValue('c') as HTMLInputElement;
    expect(radioC.checked).toBe(true);
  });

  it('未選択の場合にエラーメッセージが表示されること', () => {
    render(<WithError />);
    expect(screen.getByText('選択が必要です')).toBeInTheDocument();
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
