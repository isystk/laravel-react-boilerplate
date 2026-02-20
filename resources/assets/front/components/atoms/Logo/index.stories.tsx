import type { Meta, StoryFn } from '@storybook/react';
import Logo from './index';

export default {
  title: 'Components/Atoms/Logo',
  component: Logo,
  tags: ['autodocs'],
} as Meta<typeof Logo>;

export const WithLink: StoryFn = () => <Logo hasLink={true} src="/assets/images/logo.png" />;
export const WithoutLink: StoryFn = () => <Logo hasLink={false} src="/assets/images/logo.png" />;
