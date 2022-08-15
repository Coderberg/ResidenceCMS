let Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('js/app', './assets/app.js')
    .addEntry('js/admin', './assets/js/admin/admin.js')
    .addEntry('js/auth', './assets/js/auth/auth.js')
    .addEntry('js/ekko-lightbox', './assets/js/ekko-lightbox.js')
    .addEntry('js/map', './assets/js/map.js')
    .addEntry('js/menu-sorting', './assets/js/admin/menu-sorting.js')
    .addEntry('js/select2', './assets/js/select2.js')
    .addEntry('js/settings', './assets/js/admin/settings.js')
    .addEntry('js/slugger', './assets/js/slugger.js')
    .addEntry('js/city', './assets/js/city.js')
    .addEntry('js/photo', './assets/js/photo.js')
    .addEntry('js/page', './assets/js/page.js')
    .addEntry('js/user', './assets/js/user/user.js')
    .addEntry('js/password', './assets/js/user/password/password.js')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    //.splitEntryChunks()

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')

    .addStyleEntry('css/app', ['./assets/styles/app.scss'])
    .addStyleEntry('css/admin', ['./assets/styles/admin.scss'])
    .addStyleEntry('css/ekko-lightbox', ['./assets/styles/ekko-lightbox.scss'])
    .addStyleEntry('css/city', ['./assets/styles/city.scss'])
    .addStyleEntry('css/detail', ['./assets/styles/detail.scss'])
    .addStyleEntry('css/photo', ['./assets/styles/photo.scss'])
    .addStyleEntry('css/select2', ['./assets/styles/select2.scss'])
    //.enableIntegrityHashes()
    .configureBabel(null, {
        useBuiltIns: 'usage',
        corejs: 3
    })

;

module.exports = Encore.getWebpackConfig();
