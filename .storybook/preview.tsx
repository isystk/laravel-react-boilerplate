import type { Preview } from '@storybook/react';

import '@/assets/styles/app.scss';
import {AppRoot} from '@/app';

export const decorators = [
  (Story) => (
    <AppRoot>
      <Story />
    </AppRoot>
  ),
];

export const parameters: Preview['parameters'] = {
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /Date$/i,
    },
  },
};
