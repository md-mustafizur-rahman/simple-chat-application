const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js');

mix.webpackConfig({
    resolve: {
        fallback: {
            "process": require.resolve("process/browser")
        }
    }
});
