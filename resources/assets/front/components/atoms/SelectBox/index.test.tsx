import { describe, it, expect } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';

const { Default, WithError, WithLaravelError } = composeStories(stories);

describe('SelectBox Storybook Tests', () => {
  it('Default: should render select box with options and label', () => {
    render(<Default />);
    expect(screen.getByLabelText('選択肢')).toBeInTheDocument();
    expect(screen.getByText('オプション1')).toBeInTheDocument();
    expect(screen.getByText('オプション2')).toBeInTheDocument();
    expect(screen.getByText('オプション3')).toBeInTheDocument();
  });

  it('Default: should select option and call onChange', () => {
    render(<Default />);
    const select = screen.getByLabelText('選択肢') as HTMLSelectElement;
    fireEvent.change(select, { target: { value: 'option2' } });
    expect(select.value).toBe('option2');
  });

  it('WithError: should display error message', () => {
    render(<WithError />);
    expect(screen.getByText('このフィールドは必須です。')).toBeInTheDocument();
  });

  it('WithLaravelError: should display Laravel error message from window object', () => {
    window.laravelErrors = {
      'select-box': ['Laravelからのエラーメッセージです。'],
    };
    render(<WithLaravelError />);
    expect(screen.getByText('Laravelからのエラーメッセージです。')).toBeInTheDocument();
  });
});
