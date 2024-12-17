const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

mix.js('resources/js/bus.js', 'public/js')
    .css('resources/css/bus.css', 'public/css');

mix.js('resources/js/schemas/create.js', 'public/js/schemas')
    .css('resources/css/schemas/create.css', 'public/css/schemas');
mix.js('resources/js/owl.carousel.min.js', 'public/js/')
    .css('resources/css/owl.carousel.min.css', 'public/css/')
    .css('resources/css/owl.theme.default.min.css', 'public/css/');

// Добавляем версионирование файлов
mix.version();

// Включаем BrowserSync для автоматического обновления страницы
mix.browserSync({
    proxy: 'your-project.test',
    files: [
        'resources/views/**/*.php',
        'resources/js/**/*.js',
        'resources/css/**/*.css'
    ]
});
