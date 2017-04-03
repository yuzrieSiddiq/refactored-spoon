const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

const papaparsePath = './node_modules/papaparse/'
mix.copy(papaparsePath + 'papaparse.min.js', 'public/js/papaparse.min.js');

const jQueryPath = './node_modules/jquery/dist/';
mix.copy(jQueryPath + 'jquery.min.js', 'public/js/jquery.min.js');

const bootstrapJSPath = './node_modules/bootstrap-sass/assets/javascripts/';
mix.copy(bootstrapJSPath + 'bootstrap.min.js', 'public/js/bootstrap.min.js');
