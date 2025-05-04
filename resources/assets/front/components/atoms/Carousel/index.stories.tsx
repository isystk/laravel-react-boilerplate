import Carousel from './index';
import type { Meta, StoryFn } from '@storybook/react';

import banner01Image from '@/assets/images/banner_01.jpg';
import banner02Image from '@/assets/images/banner_02.jpg';

export default {
  title: 'Components/Atoms/Carousel',
  component: Carousel,
  tags: ['autodocs'],
} as Meta<typeof Carousel>;

const sampleImages = [
  { src: banner01Image as string, alt: 'Slide 1' },
  { src: banner02Image as string, alt: 'Slide 2' },
  { src: banner01Image as string, alt: 'Slide 3' },
  { src: banner02Image as string, alt: 'Slide 4' },
];

export const Default: StoryFn = () => <Carousel images={sampleImages} />;

export const WithAutoPlay: StoryFn = () => (
  <Carousel images={sampleImages} autoPlay autoPlayInterval={5000} />
);
