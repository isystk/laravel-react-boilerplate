import { useState } from 'react';
import ImageInput from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Atoms/ImageInput',
  component: ImageInput,
  tags: ['autodocs'],
} as Meta<typeof ImageInput>;

export const Default: StoryFn = () => {
  const [value] = useState('');
  return (
    <ImageInput
      identity="profile_picture"
      label="プロフィール画像"
      value={value}
      noImage="/assets/images/no_image.png"
    />
  );
};

export const WithError: StoryFn = () => (
  <ImageInput
    identity="profile_picture"
    label="プロフィール画像"
    value=""
    error="画像は必須です"
    required={true}
  />
);

export const WithLaravelError: StoryFn = () => {
  if (typeof window !== 'undefined') {
    window.laravelErrors = {
      profile_picture: ['Laravelからのエラーです'],
    };
  }

  return (
    <ImageInput identity="profile_picture" label="プロフィール画像" value="" required={true} />
  );
};

export const WithPreview: StoryFn = () => {
  const [value] = useState('');
  return <ImageInput identity="profile_picture" label="プロフィール画像" value={value} />;
};
