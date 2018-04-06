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

    // Style
    .addStyleEntry('core', './assets/scss/public/core.scss')
    .addStyleEntry('registration', './assets/scss/public/registration.scss')

    // Javascript
    .addEntry('form', './assets/javascript/components/form.js')
    .addEntry('snackbar', './assets/javascript/components/form/snackbar.js')

    // Vue
    .addEntry('vue', './assets/vue/public/index.js')
;

module.exports = Encore.getWebpackConfig();
