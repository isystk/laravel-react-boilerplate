const path = require('path');
module.exports = {
  typescript: {
    reactDocgen: false
  },
  webpackFinal: async config => {
    // scss
    config.module.rules.push({
      test: /\.scss$/,
      use: ['style-loader', 'css-loader', 'sass-loader', {
        loader: "postcss-loader",
        options: {
          // HERE: OPTIONS
          postcssOptions: {
            plugins: [require("tailwindcss")]
          }
        }
      }],
      include: path.resolve(__dirname, '../src/assets/sass')
    });
    config.resolve = {
        ...config.resolve,
        fallback: {
            ...(config.resolve || {}).fallback,
            fs: false,
            stream: false,
            os: false,
            zlib: false,
        },
        // alias
        alias: {
          '@': path.resolve(__dirname, '../src')
        }
    }
    return config;
  },
  stories: ['../src/**/*.stories.mdx', '../src/**/*.stories.@(js|jsx|ts|tsx)'],
  addons: ['@storybook/addon-links', '@storybook/addon-essentials', '@storybook/addon-interactions', "storybook-addon-next-router"],
  staticDirs: ['../public'],
  framework: '@storybook/react',
  core: {
    builder: 'webpack5'
  },
  docs: {
    autodocs: true
  }
};