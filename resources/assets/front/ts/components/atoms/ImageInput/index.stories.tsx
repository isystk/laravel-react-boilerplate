import { JSX, useState } from 'react';
import ImageInput from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Atoms/ImageInput',
  component: ImageInput,
  tags: ['autodocs'],
} as Meta<typeof ImageInput>;

export const Default: StoryFn = () => {
  const [value, setValue] = useState('');
  return <ImageInput identity="profile_picture" label="プロフィール画像" value={value} />;
};

export const WithError: StoryFn = () => (
  <ImageInput identity="profile_picture" label="プロフィール画像" value="" error="画像は必須です" />
);

export const WithPreview: StoryFn = () => {
  const [value, setValue] = useState('');
  return <ImageInput identity="profile_picture" label="プロフィール画像" value={value} />;
};
