import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
// import react from '@vitejs/plugin-react'
// import vue from '@vitejs/plugin-vue'

const adminConfig = defineConfig({
    base: '/assets/admin/dist',
    plugins: [
        laravel([
            'resources/src/admin/sass/style.scss',
            'resources/src/admin/js/app.js',
        ]),
        // react(),
        // vue({
        //     template: {
        //         transformAssetUrls: {
        //             base: null,
        //             includeAbsolute: false,
        //         },
        //     },
        // }),
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
    // frontConfig.build = {
    //   ...frontConfig.build,
    //   minify: true,
    //   manifest: true
    // }
    adminConfig.build = {
      ...adminConfig.build,
      minify: true,
      manifest: true
    }
  } else {
    // 本番以外の環境
    // frontConfig.build = {
    //   ...frontConfig.build,
    //   sourcemap: true
    // }
    adminConfig.build = {
      ...adminConfig.build,
      // sourcemap: true
    }
  }
  return adminConfig;
};
