import type { Meta, StoryFn } from '@storybook/react';
import Image from './index';

export default {
  title: 'Components/Atoms/Image',
  component: Image,
  tags: ['autodocs'],
} as Meta<typeof Image>;

export const Default: StoryFn = () => (
  <Image
    src="/assets/images/profile.jpeg"
    alt="サンプル画像"
    width={200}
    height={100}
    className="border"
  />
);

export const WithLazyLoading: StoryFn = () => (
  <Image
    src="/assets/images/profile.jpeg"
    alt="遅延読み込み画像"
    loading="lazy"
    width={300}
    height={150}
  />
);

export const WithEagerLoading: StoryFn = () => (
  <Image
    src="/assets/images/profile.jpeg"
    alt="即時読み込み画像"
    loading="eager"
    width={300}
    height={150}
  />
);
