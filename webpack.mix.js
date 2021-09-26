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

mix.styles([
    'public/css/all.fontawesome.min.css',
    'public/css/adminlte.min.css',
    'public/css/daterangepicker.css',
    'public/css/select2.min.css',
    'public/css/select2-bootstrap4.min.css',
    'public/css/datatables.min.css',
], 'public/css/mripta.min.css');

mix.scripts([
    'public/js/jquery.min.js',
    'public/js/bootstrap.bundle.min.js',
    'public/js/adminlte.min.js',
    'public/js/chart.min.js',
    'public/js/moment.min.js',
    'public/js/daterangepicker.js',
    'public/js/select2.full.min.js',
    'public/js/pdfmake.min.js',
    'public/js/vfs_fonts.js',
    'public/js/datatables.min.js'
], 'public/js/mripta.min.js');