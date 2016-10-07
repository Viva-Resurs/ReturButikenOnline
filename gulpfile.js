const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {

    /* Sass */
    mix
        .sass('app.scss')

    /* Fonts */
    mix
        .copy( 'resources/assets/fonts', 'public/fonts' )

    /* Application JS */
    mix
       .webpack('app.js',false,false,{
            resolve: {
              alias: {
                vue: 'vue/dist/vue.js' // To use templates in .vue files
              }
            }
       });

});
