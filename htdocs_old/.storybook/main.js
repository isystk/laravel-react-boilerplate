const path = require('path')

module.exports = {
  staticDirs: ['../public'],
  webpackFinal: async (config) => {
      // scss
      config.module.rules.push({
          test: /\.scss$/,
          use: ['style-loader', 'css-loader', 'sass-loader'],
          include: path.resolve(__dirname, '../resources/src/front/sass'),
      });
      // alias
      config.resolve.alias = {
          ...config.resolve.alias,
          "@": path.resolve(__dirname, '../resources/src/front/ts'),
      }
      return config
  },
  "stories": [
    "../resources/src/front/ts/stories/**/*.stories.mdx",
    "../resources/src/front/ts/stories/**/*.stories.@(js|jsx|ts|tsx)"
  ],
  "addons": [
    "@storybook/addon-links",
    "@storybook/addon-essentials",
    "@storybook/addon-interactions",
    "@storybook/preset-scss"
  ],
  "framework": "@storybook/react"
}
