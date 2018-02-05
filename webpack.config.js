var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .addStyleEntry('core', './assets/scss/public/core.scss')
    .addStyleEntry('registration', './assets/scss/public/registration.scss')
;

module.exports = Encore.getWebpackConfig();
