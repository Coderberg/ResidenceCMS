const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
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
    .addEntry('js/google_authenticator', './assets/js/user/two_factor/google_authenticator.js')
    .addEntry('js/bottom-bar', './assets/js/bottom-bar.js')

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
    .addStyleEntry('css/bottom-bar', ['./assets/styles/bottom-bar.scss'])
    .addStyleEntry('css/security', ['./assets/styles/user/security.scss'])
    //.enableIntegrityHashes()
    .configureBabel(null, {
        useBuiltIns: 'usage',
        corejs: 3
    })

;

module.exports = Encore.getWebpackConfig();
