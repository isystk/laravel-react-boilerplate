const path = require('path')
const pkg = require('./package')

const nextConfig = {
  env: {
    APP_NAME: pkg.name,
    APP_DESCRIPTION: pkg.description,
  },
  webpack: (config, { isServer }) => {
    // src ディレクトリをエイリアスのルートに設定
    config.resolve.alias['@'] = path.resolve(__dirname, 'src')
    
//    // Fontを読み込めるようにする
//    config.module.rules.push({
//      test: /\.(woff|woff2|eot|ttf|otf)$/,
//      use: {
//        loader: 'file-loader',
//        options: {
//          name: '[name].[ext]',
//          outputPath: 'static/fonts/',
//          publicPath: '/_next/static/fonts/',
//        },
//      },
//    });
    
    return config
  },
  typescript: {
    // ビルド時のTypescriptエラーを無視する
    ignoreBuildErrors: true,
  }
}

module.exports = nextConfig
