const Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require("copy-webpack-plugin");

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableBuildNotifications()
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

    // PWA
    .addEntry('serviceWorker', './assets/javascript/pwa/app.js')

    // Style
    .addStyleEntry('app-shell', './assets/scss/public/app-shell.scss')
    .addStyleEntry('homepage', './assets/scss/public/homepage.scss')
    .addStyleEntry('core', './assets/scss/public/core.scss')
    .addStyleEntry('register', './assets/scss/public/register.scss')
    .addStyleEntry('dashboard', './assets/scss/public/dashboard.scss')

    // Javascript
    .addEntry('snackbar', './assets/javascript/public/Snackbar.js')
    .addEntry('navigation', './assets/javascript/public/navigation.js')
    .addEntry('mc', './assets/javascript/public/mc.js')
    .addEntry('askResetPassword', './assets/javascript/public/askResetPassword.js')
    .addEntry('file_check', './assets/javascript/components/form/file_check.js')
    .addEntry('login', './assets/javascript/public/login.js')
    .addEntry('stock_tags', './assets/javascript/components/form/stock_tags.js')
    .addEntry('registrationForm', './assets/javascript/public/register.js')
    .addEntry('collectionHandler', './assets/javascript/components/UI/Form/CollectionHandler.js')
    .addEntry('stockCreation', './assets/javascript/public/stockCreation.js')

    // Polymer
    .addEntry('snackbar_polymer', './assets/javascript/polymer/components/UI/snackbar.js')
;

if (Encore.isProduction()) {
    Encore
        //.enableVersioning()
        .configureUglifyJsPlugin()
        .configureUrlLoader()
    ;
}

module.exports = Encore.getWebpackConfig();
