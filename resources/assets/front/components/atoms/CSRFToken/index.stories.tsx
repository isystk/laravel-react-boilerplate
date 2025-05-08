import CSRFToken from './index';
import type { Meta } from '@storybook/react';
import { JSX } from 'react';

export default {
  title: 'Components/Atoms/CSRFToken',
  component: CSRFToken,
  tags: ['autodocs'],
  decorators: [
    Story => {
      // モック用の meta タグをセットアップ
      const meta = document.createElement('meta');
      meta.name = 'csrf-token';
      meta.content = 'csrf-token';
      document.head.appendChild(meta);
      return <Story />;
    },
  ],
} as Meta<typeof CSRFToken>;

export const Default: { render: () => JSX.Element } = {
  render: () => <CSRFToken />,
};
