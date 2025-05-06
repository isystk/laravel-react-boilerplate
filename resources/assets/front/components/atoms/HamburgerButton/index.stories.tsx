import type { Meta, StoryObj } from '@storybook/react';
import HamburgerButton from './index';
import { MINIMAL_VIEWPORTS } from '@storybook/addon-viewport';

export default {
  title: 'Components/Atoms/HamburgerButton',
  component: HamburgerButton,
  tags: ['autodocs'],
  parameters: {
    viewport: {
      viewports: MINIMAL_VIEWPORTS,
    },
  },
} as Meta<typeof HamburgerButton>;

export const Default: StoryObj<typeof HamburgerButton> = {
  render: args => <HamburgerButton {...args} />,
  args: {
    isOpen: false,
    onClick: open => console.log(`Hamburger is now ${open ? 'open' : 'closed'}`),
  },
};
