import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

const config = defineConfig({
    base: '/assets/admin/dist',
    plugins: [
        laravel({
            input: [
                'resources/src/admin/sass/style.scss',
                'resources/src/admin/js/app.js',
            ],
            refresh: true,
        })
    ],
    build: {
        outDir: 'public/assets/admin/dist',
        assetsDir: '',
        rollupOptions: {
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`,
            }
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
      sourcemap: true
    }
  }
  return config;
};
