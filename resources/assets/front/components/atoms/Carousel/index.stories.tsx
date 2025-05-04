import Carousel from './index';
import type { Meta, StoryFn } from '@storybook/react';

export default {
  title: 'Components/Atoms/Carousel',
  component: Carousel,
  tags: ['autodocs'],
} as Meta<typeof Carousel>;

const sampleImages = [
  { src: '/assets/images/banner_01.jpg' as string, alt: 'Slide 1' },
  { src: '/assets/images/banner_02.jpg' as string, alt: 'Slide 2' },
  { src: '/assets/images/banner_01.jpg' as string, alt: 'Slide 3' },
  { src: '/assets/images/banner_02.jpg' as string, alt: 'Slide 4' },
];

export const Default: StoryFn = () => <Carousel images={sampleImages} />;

export const WithAutoPlay: StoryFn = () => (
  <Carousel images={sampleImages} autoPlay autoPlayInterval={5000} />
);
