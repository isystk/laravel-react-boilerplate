import { useState } from 'react';
import type { Meta, StoryFn } from '@storybook/react';
import { ToastMessage, ToastTypes } from './index';

export default {
  title: 'Components/Interactions/ToastMessage',
  component: ToastMessage,
  tags: ['autodocs'],
} as Meta<typeof ToastMessage>;

export const Alert: StoryFn = () => {
  const [open, setOpen] = useState(true);

  return (
    <ToastMessage
      isOpen={open}
      type={ToastTypes.Alert}
      message="アラートメッセージです。"
      onConfirm={() => {
        alert('確認されました');
        setOpen(false);
      }}
    />
  );
};

export const Confirm: StoryFn = () => {
  const [open, setOpen] = useState(true);

  return (
    <ToastMessage
      isOpen={open}
      type={ToastTypes.Confirm}
      message="この操作を実行しますか？"
      onConfirm={() => {
        alert('はいが選択されました');
        setOpen(false);
      }}
      onCancel={() => {
        alert('いいえが選択されました');
        setOpen(false);
      }}
    />
  );
};
