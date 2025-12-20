import ScrollTopButton from './index';
import type { StoryFn } from '@storybook/react';

export default {
  title: 'Components/Interactions/ScrollTopButton',
  component: ScrollTopButton,
  parameters: {
    layout: 'fullscreen',
  },
  tags: ['autodocs'],
};

export const Default: StoryFn = () => {
  return (
    <div style={{ height: '2000px', padding: '16px' }}>
      <p>スクロールしてボタンが表示されるのを確認してください。</p>
      <ScrollTopButton />
    </div>
  );
};

export const DisplayAfterScroll: StoryFn = () => {
  if (typeof window !== 'undefined') {
    setTimeout(() => {
      window.scrollTo(0, 500);
    }, 1000);
  }

  return (
    <div style={{ height: '2000px', padding: '16px' }}>
      <p>スクロールしてボタンが表示されるのを確認してください。</p>
      <ScrollTopButton />
    </div>
  );
};
