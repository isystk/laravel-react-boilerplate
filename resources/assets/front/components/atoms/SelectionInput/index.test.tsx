import { describe, it, expect, vi } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { composeStories } from '@storybook/react';
import * as stories from './index.stories';
import '@testing-library/jest-dom';
import SelectionInput from './index';

const { CheckboxDefault, RadioDefault, WithError, WithLaravelError } = composeStories(stories);

describe('SelectionInput Storybook Tests', () => {
  it('CheckboxDefault: should render checkboxes with labels', () => {
    render(<CheckboxDefault />);
    expect(screen.getByRole('group', { name: 'チェックボックス' })).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢A')).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢B')).toBeInTheDocument();
    expect(screen.getByLabelText('選択肢C')).toBeInTheDocument();
  });

  it('RadioDefault: should render radio buttons and check selected value', () => {
    render(<RadioDefault />);
    const radioC = screen.getByDisplayValue('c') as HTMLInputElement;
    expect(radioC.checked).toBe(true);
  });

  it('WithError: should show error message', () => {
    render(<WithError />);
    expect(screen.getByText('選択が必要です')).toBeInTheDocument();
  });

  it('WithLaravelError: should show Laravel error from window', () => {
    window.laravelErrors = {
      radio: ['Laravelからのエラーです'],
    };
    render(<WithLaravelError />);
    expect(screen.getByText('Laravelからのエラーです')).toBeInTheDocument();
  });

  it('should call onChange when checkbox is clicked', () => {
    const handleChange = vi.fn();
    render(
      <SelectionInput
        identity="test"
        controlType="checkbox"
        label="選択"
        options={[{ label: 'A', value: 'a' }]}
        checkedValues={[]}
        onChange={handleChange}
      />,
    );
    const checkbox = screen.getByLabelText('A') as HTMLInputElement;
    fireEvent.click(checkbox);
    expect(handleChange).toHaveBeenCalled();
  });
});
