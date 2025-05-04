import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, WithError, LaravelError } = composeStories(stories);

describe('TextArea', () => {
  it('Default: should render textarea with default value', () => {
    render(<Default />);
    expect(screen.getByLabelText('お問い合わせ内容')).toHaveValue('初期値です');
  });

  it('WithError: should show error message', () => {
    render(<WithError />);
    expect(screen.getByText('必須項目です')).toBeInTheDocument();
  });

  it('LaravelError: should show error from Laravel window object', () => {
    render(<LaravelError />);
    expect(screen.getByText('Laravel側のエラーです')).toBeInTheDocument();
  });

  it('LaravelError: should clear Laravel error from window after rendering', () => {
    window.laravelErrors = {
      message: ['一度表示される Laravel エラー'],
    };
    render(<LaravelError />);
    expect(window.laravelErrors.message).toBeUndefined();
  });
});
