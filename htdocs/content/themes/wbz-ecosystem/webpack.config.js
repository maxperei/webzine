var webpack = require('webpack'),
    path = require('path');

module.exports = {
    cache: true,
    target: 'web',
    entry: {
        theme: path.join(__dirname, 'assets/js/theme.js'),
        widget: path.join(__dirname, 'assets/js/widget.js'),
    },
    output: {
        path: path.join(__dirname, 'dist/js'),
        publicPath: '',
        filename: '[name].min.js'
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            },
            {
                test: path.resolve('angular'),
                loader: 'exports?window.angular'
            }
        ]
    },
    externals: {
        jquery: 'jQuery',
        backbone: 'Backbone',
        underscore: '_',
        angular: 'angular'
    },
    plugins: [
        new webpack.ProvidePlugin({
            // Automatically detect jQuery and $ as free var in modules
            // and inject the jquery library
            // This is required by many jquery plugins
            jquery: "jQuery",
            $: "jQuery",
            backbone: "Backbone",
            underscore: "_",
            angular: "angular",
            'window.jQuery': 'jquery'
        })
    ]
};