import FlashMessage, { MessageTypes } from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Interactions/FlashMessage',
  component: FlashMessage,
  tags: ['autodocs'],
} as Meta<typeof FlashMessage>;

export const WithMessage: StoryFn = () => (
  <FlashMessage message="これはフラッシュメッセージです。" />
);

export const ErrorMessage: StoryFn = () => (
  <FlashMessage message="これはエラーメッセージです。" type={MessageTypes.Error} />
);

export const LaravelSessionMessage: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelSession = {
      status: 'Laravel側のメッセージです',
    };
  }

  return <FlashMessage />;
};
