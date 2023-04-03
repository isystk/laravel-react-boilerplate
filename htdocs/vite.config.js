import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import path from 'path';
import react from '@vitejs/plugin-react'

const config = defineConfig({
    base: '/assets/front/dist',
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/src/front/ts")
        }
    },
    define: {
        global: {},
        process: {
            env: {
                NODE_ENV: 'development',
            }
        }
    },
    plugins: [
        laravel([
            'resources/src/front/sass/app.scss',
            'resources/src/front/ts/app.tsx',
        ]),
        react(),
    ],
    build: {
        outDir: 'public/assets/front/dist',
        assetsDir: '',
        rollupOptions: {
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`,
            },
            plugins: [
                // CommonJSモジュールをESモジュールに変換するプラグイン
                require('@rollup/plugin-commonjs')()
            ]
        }
    }
})

export default ({ mode }) => {
  if (mode === 'production') {
    // 本番環境
    config.build = {
      ...config.build,
      minify: true,
      manifest: true
    }
  } else {
    // 本番以外の環境
    config.build = {
      ...config.build,
      sourcemap: false
    }
  }
  return config;
};
