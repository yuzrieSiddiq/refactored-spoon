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

const datatablesCoreJSPath = './node_modules/datatables.net/js/';
mix.copy(datatablesCoreJSPath + 'jquery.dataTables.js', 'public/js/datatables.net.js');

const datatablesCoreBootstrapJSPath = './node_modules/datatables.net-bs/js/';
mix.copy(datatablesCoreBootstrapJSPath + 'dataTables.bootstrap.js', 'public/js/datatables.bootstrap.js');

const datatablesCoreBootstrapCSSPath = './node_modules/datatables.net-bs/css/';
mix.copy(datatablesCoreBootstrapCSSPath + 'dataTables.bootstrap.css', 'public/css/datatables.bootstrap.css');

const chartJSPath = './node_modules/chart.js/dist/';
mix.copy(chartJSPath + 'Chart.min.js', 'public/js/Chart.min.js');

const chartJSPieceLabelPath = './node_modules/chart.piecelabel.js/build/';
mix.copy(chartJSPieceLabelPath + 'Chart.PieceLabel.min.js', 'public/js/chart.piecelabel.min.js');

const momentJSPath = './node_modules/moment/min/';
mix.copy(momentJSPath + 'moment.min.js', 'public/js/moment.min.js');

const twbsPaginationJSPath = './node_modules/twbs-pagination/';
mix.copy(twbsPaginationJSPath + 'jquery.twbsPagination.min.js', 'public/js/twbs-pagination.min.js');
