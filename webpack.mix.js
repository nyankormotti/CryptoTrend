const mix = require('laravel-mix');

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

mix.js('resources/js/trend.js', 'public/js/trend.js')
    .js('resources/js/account.js', 'public/js/account.js')
    .js('resources/js/news.js', 'public/js/news.js')
    .js('resources/js/main.js', 'public/js/main.js')
    .sass('resources/sass/app.scss', 'public/css');
