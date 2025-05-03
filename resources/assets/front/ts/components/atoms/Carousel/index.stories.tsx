import Carousel from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Atoms/Carousel',
  component: Carousel,
  tags: ['autodocs'],
} as Meta<typeof Carousel>;

const sampleImages = [
  { src: 'https://localhost/assets/front/image/banner_01.jpg', alt: 'Slide 1' },
  { src: 'https://localhost/assets/front/image/banner_02.jpg', alt: 'Slide 2' },
  { src: 'https://localhost/assets/front/image/banner_01.jpg', alt: 'Slide 3' },
  { src: 'https://localhost/assets/front/image/banner_02.jpg', alt: 'Slide 4' },
];

export const Default: StoryFn = () => <Carousel images={sampleImages} />;

export const WithAutoPlay: StoryFn = () => (
  <Carousel images={sampleImages} autoPlay autoPlayInterval={5000} />
);
