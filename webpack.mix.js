const mix = require('laravel-mix');
require('laravel-mix-pluton');

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

mix.setPublicPath('./wp-content/themes/dw/public')
    .js('wp-content/themes/dw/resources/js/script.js', 'wp-content/themes/dw/public/js')
    .sass('wp-content/themes/dw/resources/sass/style.scss', 'wp-content/themes/dw/public/css')
    .options({
        processCssUrls: false
    })
    .browserSync({
        proxy: 'localhost:8888/DW-projet/wordpress-intro-2022/', // DW-projet/wordpress-intro-2022.localhost
        notify: false
    })
    .version();
