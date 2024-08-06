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
    .addEntry('app', ['./assets/app.js','./assets/js/headerResponsive.js'])
    .addEntry('login','./assets/styles/pages/_login.scss')
    .addEntry('homepage','./assets/styles/pages/_homepage.scss')
    .addEntry('allArticlesPage','./assets/styles/pages/_allArticlesPage.scss')
    .addEntry('articlePageDetail','./assets/styles/pages/_articlePageDetail.scss')
    .addEntry('createArticlePage',['./assets/js/pages/create_or_update_article.js','./assets/styles/pages/_pageCreateArticle.scss'])
    .addEntry("dashboard",'./assets/styles/pages/_dashboard.scss')
    .addEntry('demandToBecomeMember',['./assets/styles/pages/_demand_to_become_member.scss', './assets/js/pages/demand_to_become_member.js'])
    .addEntry('allDemandsPage',['./assets/styles/pages/_allDemandsPage.scss'] )
    .addEntry('demandDetailPage',['./assets/styles/pages/_demandDetailPage.scss'])
    .addEntry('allPodcasts',['./assets/styles/pages/_allPodcasts.scss'])
    .addEntry('audioPlayer',['./assets/js/pages/audioPlayer.js'])

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

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

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()
    .enableStimulusBridge('./assets/controllers.json') // configuration de Stimulus
    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();