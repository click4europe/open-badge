let mix = require('laravel-mix');
mix.disableNotifications();
mix.options({
  processCssUrls: false
});
mix.webpackConfig({ watchOptions: {
    aggregateTimeout: 900,
    ignored:  ['**/css/*.css', '**/node_modules', '**/mix-manifest.json'] 
  }, 
});

mix.postCss('src/input.css', 'css/style.css', [
    require("@tailwindcss/postcss", {}),
    require("autoprefixer", {}),
    require("postcss-nested", {}),
    require("postcss-partial-import", {prefix: '_'}),
    require("postcss-custom-media", {}),
    require("postcss-at-rules-variables", {}),
    require("postcss-for", {}),
    require("postcss-each-variables", {}),
    require("postcss-each", {}),
]);

mix.js('js/app.js', 'js/main.js');
