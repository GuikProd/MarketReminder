const Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require("copy-webpack-plugin");

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())
    .enableTypeScriptLoader()
    .enableVueLoader()
    .enableSassLoader()
    .addLoader(
        {
            test: /\.scss$/,
            use: [
                {
                    loader: 'sass-loader',
                    options: {
                        importer: function(url, prev) {
                            if(url.indexOf('@material') === 0) {
                                const filePath = url.split('@material')[1];
                                const nodeModulePath = `./node_modules/@material/${filePath}`;
                                return {
                                    file: require('path').resolve(nodeModulePath)
                                };
                            }
                            return {
                                file: url
                            };
                        }
                    }
                }
            ]
        }
    )
    .addLoader(
        {
            test: /\.js$/,
            loader: 'babel-loader',
            query: {
                presets: ['es2015']
            }
        }
    )
    .addPlugin(new CopyWebpackPlugin([
        {
            from: "node_modules/@webcomponents/webcomponentsjs/*js",
            to: "webcomponents/",
            flatten: true
        }
    ]))

    // Style
    .addStyleEntry('core', './assets/scss/public/core.scss')
    .addStyleEntry('register', './assets/scss/public/register.scss')
    .addStyleEntry('registration', './assets/scss/public/registration.scss')
    .addStyleEntry('dashboard', './assets/scss/public/dashboard.scss')

    // Javascript
    .addEntry('mc', './assets/javascript/public/mc.js')
    .addEntry('collectionHandler', './assets/javascript/public/form.js')
    .addEntry('form', './assets/javascript/components/form.js')
    .addEntry('file_check', './assets/javascript/components/form/file_check.js')
    .addEntry('snackbar', './assets/javascript/components/form/snackbar.js')
    .addEntry('stock_tags', './assets/javascript/components/form/stock_tags.js')
    .addEntry('registrationForm', './assets/javascript/public/register.js')

    // PWA
    .addEntry('serviceWorker', './assets/javascript/pwa/app.js')
    .addEntry('sw', './assets/javascript/pwa/sw.js')

    // Polymer
    .addEntry('snackbar_polymer', './assets/javascript/polymer/components/UI/snackbar.js')
;

module.exports = Encore.getWebpackConfig();
