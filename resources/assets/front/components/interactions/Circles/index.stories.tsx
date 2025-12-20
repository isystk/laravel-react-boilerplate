import Circles from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Interactions/Circles',
  component: Circles,
  tags: ['autodocs'],
} as Meta<typeof Circles>;

export const Default: StoryFn = () => (
  <Circles>
    <div style={{ height: '500px' }}>Some content inside Circles component</div>
  </Circles>
);
