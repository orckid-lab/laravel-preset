const mix = require('laravel-mix');
require('dotenv').config();

mix
  .js('resources/assets/js/app.js', 'public/js')
  .less('resources/assets/less/app.less', 'public/css');

if (!mix.inProduction()) {
  mix
    .webpackConfig({
      module: {
        rules: [
          {
            test: /\.(js|vue)$/,
            exclude: /(node_modules|bower_components)/,
            loader: 'eslint-loader',
            enforce: 'pre',
            options: {
              formatter: require('eslint-friendly-formatter')
            }
          }
        ]
      },
      devServer: {overlay: true}
    })
    .sourceMaps()
    .browserSync({
      proxy: process.env.APP_URL,
      files: [
        'resources/views/**/*.php',
        'public/js/**/*.js',
        'public/css/**/*.css'
      ]
    });
}

if (mix.inProduction()) {
  mix.disableNotifications();

  mix.version();
}
