import { useState } from 'react';
import TextInput from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Atoms/TextInput',
  component: TextInput,
  tags: ['autodocs'],
  args: {
    identity: 'email',
    controlType: 'text',
    label: 'メールアドレス',
    required: true,
    autoComplete: 'email',
    className: 'mb-4',
  },
} as Meta<typeof TextInput>;

export const Default: StoryFn = () => {
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
  />
);

export const LaravelErrorSimulated: StoryFn = () => {
  // Laravel error is picked up inside TextInput via `window.laravelErrors`
  if (typeof window !== 'undefined') {
    window.laravelErrors = {
      email: ['Laravelからのエラーメッセージです。'],
    };
  }

  return (
    <TextInput
      identity="email"
      controlType="text"
      label="メールアドレス"
      value=""
      onChange={() => {}}
    />
  );
};
