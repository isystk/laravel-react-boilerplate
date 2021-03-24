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

  var HardSourceWebpackPlugin = require('hard-source-webpack-plugin');
  mix
  .sourceMaps(true)
  .webpackConfig({
    plugins: [
      // Webpackのコンパイル速度改善
      // See：https://qiita.com/Te2/items/4b9dce89950d00d344ea
      new HardSourceWebpackPlugin()
    ],
    module: {
      rules: [
        {
           test: /\.(ts|tsx)?$/,
           loader: 'ts-loader',
           exclude: /node_modules/,
        },
        {
          enforce: 'pre', // preを指定することで、付いてないローダーより先に実行できる。
          test: /\.(js|jsx|ts|tsx)?$/,
          loader: 'eslint-loader',
          exclude: /node_modules/,
          options: {
            fix: true // Lint実行時に自動整形を行うかどうか。（prettierのルールで自動整形してくれる）
          }
        },
      ],
    },
  })
}
