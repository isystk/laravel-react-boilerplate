import type { Meta, StoryFn } from '@storybook/react';
import SessionAlert from './index';

export default {
  title: 'Components/Atoms/SessionAlert',
  component: SessionAlert,
  tags: ['autodocs'],
} as Meta<typeof SessionAlert>;

export const DefaultMessage: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelSession = {
      success: '登録が完了しました',
    };
  }

  return <SessionAlert target="success" />;
};

export const ResentMessage: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelSession = {
      resent: '本来のメッセージ', // 無視される
    };
  }

  return <SessionAlert target="resent" />;
};

export const NoMessage: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelSession = {};
  }

  return <SessionAlert target="missing" />;
};
