import type { Meta, StoryFn } from '@storybook/react';
import SelectionInput from './index';

export default {
  title: 'Components/Atoms/SelectionInput',
  component: SelectionInput,
  tags: ['autodocs'],
} as Meta<typeof SelectionInput>;

const options = [
  { label: '選択肢A', value: 'a' },
  { label: '選択肢B', value: 'b' },
  { label: '選択肢C', value: 'c' },
];

export const CheckboxDefault: StoryFn = () => (
  <SelectionInput
    identity="checkbox"
    label="チェックボックス"
    controlType="checkbox"
    options={options}
    checkedValues={['b']}
    required={false}
  />
);

export const RadioDefault: StoryFn = () => (
  <SelectionInput
    identity="radio"
    label="ラジオボタン"
    controlType="radio"
    options={options}
    selectedValue="c"
    required={false}
  />
);

export const WithError: StoryFn = () => (
  <SelectionInput
    identity="radio"
    label="ラジオボタン"
    controlType="radio"
    options={options}
    error="選択が必要です"
    required={true}
  />
);

export const WithLaravelError: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelErrors = {
      radio: ['Laravel側のエラーです'],
    };
  }

  return (
    <SelectionInput
      identity="radio"
      label="ラジオボタン"
      controlType="radio"
      options={options}
      required={true}
    />
  );
};
