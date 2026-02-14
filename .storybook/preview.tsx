import type { Preview } from '@storybook/react';

import '@/assets/styles/app.scss';
import AppRoot from '@/components/AppRoot';
import { MINIMAL_VIEWPORTS} from '@storybook/addon-viewport';

// RootコンポーネントをラップしてStorybook上に適用する
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
  // デフォルトのViewportを設定する
  viewport: {
    viewports: MINIMAL_VIEWPORTS,
    defaultViewport: 'responsive',
  },
};
