import type { StorybookConfig } from '@storybook/react-vite';

const config: StorybookConfig = {
  stories: [
    "../resources/assets/front/**/*.mdx",
    "../resources/assets/front/**/*.stories.@(js|jsx|mjs|ts|tsx)"
  ],
  addons: [
    "@storybook/addon-essentials",
    "@storybook/addon-onboarding",
    "@chromatic-com/storybook",
    "@storybook/experimental-addon-test"
  ],
  framework: {
    name: "@storybook/react-vite",
    options: {}
  },
  staticDirs: [
    // TODO Storybookのコードで画像ファイルをimportしても参照できないので以下で対応している
    { from: '../resources/assets/front/assets', to: '/assets' },
  ],
};
export default config;
