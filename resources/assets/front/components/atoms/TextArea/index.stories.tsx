import type { Meta, StoryFn } from '@storybook/react';
import TextArea from './index';

export default {
  title: 'Components/Atoms/TextArea',
  component: TextArea,
  tags: ['autodocs'],
} as Meta<typeof TextArea>;

export const Default: StoryFn = () => (
  <TextArea identity="message" label="お問い合わせ内容" defaultValue="初期値です" />
);

export const WithError: StoryFn = () => (
  <TextArea
    identity="message"
    label="お問い合わせ内容"
    error="必須項目です"
    value="エラーがあります"
  />
);

export const LaravelError: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelErrors = {
      message: ['Laravel側のエラーです'],
    };
  }

  return (
    <TextArea
      identity="message"
      label="お問い合わせ内容"
      defaultValue="Laravelエラー対象"
      required={true}
    />
  );
};
