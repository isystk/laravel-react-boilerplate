import { JSX, useState } from 'react';
import TextInput from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Atoms/TextInput',
  component: TextInput,
  tags: ['autodocs'],
} as Meta<typeof TextInput>;

export const Default: () => JSX.Element = () => {
  const [value, setValue] = useState('');
  return (
    <TextInput
      identity="email"
      controlType="text"
      label="メールアドレス"
      value={value}
      onChange={e => setValue(e.target.value)}
    />
  );
};

export const WithError: StoryFn = () => (
  <TextInput
    identity="email"
    controlType="text"
    label="メールアドレス"
    value=""
    error="このフィールドは必須です。"
    onChange={() => {}}
    required={true}
  />
);

export const LaravelError: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelErrors = {
      email: ['Laravelからのエラーです'],
    };
  }

  return (
    <TextInput
      identity="email"
      controlType="text"
      label="メールアドレス"
      onChange={() => {}}
      required={true}
    />
  );
};
