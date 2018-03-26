var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableVueLoader()
    .addStyleEntry('core', './assets/scss/public/core.scss')
    .addStyleEntry('registration', './assets/scss/public/registration.scss')

    .addEntry('form', './assets/javascript/components/form.js')
    .addEntry('snackbar', './assets/javascript/components/form/snackbar.js')

    .addEntry('index', './assets/typescript/vue/index.ts')
;

module.exports = Encore.getWebpackConfig();
