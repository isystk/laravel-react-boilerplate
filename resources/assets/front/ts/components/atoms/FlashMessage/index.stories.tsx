import FlashMessage from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Atoms/FlashMessage',
  component: FlashMessage,
  tags: ['autodocs'],
} as Meta<typeof FlashMessage>;

export const WithMessage: StoryFn = () => (
  <FlashMessage message="これはフラッシュメッセージです。" />
);

export const LaravelSessionMessage: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelSession = {
      status: 'Laravelからのメッセージ',
    };
  }

  return <FlashMessage />;
};
