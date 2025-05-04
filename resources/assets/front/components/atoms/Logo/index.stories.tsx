import type { Meta, StoryFn } from '@storybook/react';
import Logo from './index';
import { MemoryRouter } from 'react-router-dom';

export default {
  title: 'Components/Atoms/Logo',
  component: Logo,
  decorators: [
    Story => (
      <MemoryRouter>
        <Story />
      </MemoryRouter>
    ),
  ],
  tags: ['autodocs'],
} as Meta<typeof Logo>;

export const WithLink: StoryFn = () => <Logo hasLink={true} />;
export const WithoutLink: StoryFn = () => <Logo hasLink={false} />;
