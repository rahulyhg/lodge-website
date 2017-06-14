const { mix } = require('laravel-mix');

mix.js('assets/js/app.js', 'assets/dist')
    .sass('assets/scss/style.scss', './').options({
        processCssUrls: false
    })
    .browserSync('https://tahosalodge.dev');
