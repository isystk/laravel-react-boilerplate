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
