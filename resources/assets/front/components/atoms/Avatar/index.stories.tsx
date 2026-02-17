import Avatar from './index';
import type { Meta, StoryObj } from '@storybook/react';

const meta: Meta<typeof Avatar> = {
  title: 'Components/Atoms/Avatar',
  component: Avatar,
  tags: ['autodocs'],
  argTypes: {
    src: { control: 'text' },
    size: { control: 'number' },
    className: { control: 'text' },
  },
};

export default meta;

type Story = StoryObj<typeof Avatar>;

export const Default: Story = {
  args: {
    src: 'https://placehold.co/150x150',
    size: 40,
  },
};

export const NoImage: Story = {
  args: {
    src: null,
    size: 40,
  },
};

export const Small: Story = {
  args: {
    src: 'https://placehold.co/150x150',
    size: 24,
  },
};

export const Large: Story = {
  args: {
    src: 'https://placehold.co/150x150',
    size: 64,
  },
};
