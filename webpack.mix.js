let mix = require('laravel-mix');

mix.options({
    terser: {
        extractComments: false,
    }
}).setPublicPath('dist').js('resources/js/translatable-field.js', 'js');
