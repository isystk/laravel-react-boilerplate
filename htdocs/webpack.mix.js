const mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// フロント
mix
  //     .js('resources/src/front/js/app.js', 'public/assets/front/js')
  //     .sass('resources/src/front/sass/app.scss', 'public/assets/front/css')
  .ts('resources/src/front/ts/app.tsx', 'public/assets/front/js')
  .sass('resources/src/front/sass/app.scss', 'public/assets/front/css')

// 管理画面
mix
  .js('resources/src/admin/js/app.js', 'public/assets/admin/js')
  .sass('resources/src/admin/sass/app.scss', 'public/assets/admin/css')

if (mix.inProduction()) {
  // 本番環境
  mix.version()
} else {
  // 本番以外の環境

  // ESLintの実施
  mix.webpackConfig({
    module: {
      rules: [
        {
          enforce: 'pre',
          exclude: /node_modules/,
          loader: 'eslint-loader',
          test: /\.(js|jsx|ts|tsx)?$/,
        },
      ],
    },
  })
}
