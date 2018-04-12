var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())
    .enableTypeScriptLoader()
    .enableVueLoader()
    .addLoader({
        loader: 'sass-loader',
        options: {
            importer: function(url, prev) {
                if(url.indexOf('@material') === 0) {
                    var filePath = url.split('@material')[1];
                    var nodeModulePath = `./node_modules/@material/${filePath}`;
                    return { file: require('path').resolve(nodeModulePath) };
                }
                return { file: url };
            }
        }
    })

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
