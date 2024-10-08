const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // Entrée JavaScript
    .addEntry('app', './assets/js/app.js')

    // Entrée pour le SCSS
    .addStyleEntry('app_style', './assets/styles/components/app.scss') // Ceci génère app.css dans public/build

    // Optimiser les fichiers
    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    // Configurations diverses
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    // Configurer Babel
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    // Activer le support de SASS/SCSS
    .enableSassLoader()
;

// Exporter la configuration finale de Webpack
module.exports = Encore.getWebpackConfig();