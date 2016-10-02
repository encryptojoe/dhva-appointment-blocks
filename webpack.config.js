var LiveReloadPlugin = require('webpack-livereload-plugin');
var webpack          = require('webpack')

module.exports = {
    // entry: __dirname + "/qcs_react.js",
    entry: __dirname + "/js/index.js",
    output: {
        path: __dirname,
        filename: "js/index.min.js"
    },
    watch:true,
    devtool: 'source-map',
    plugins: [
        // new LiveReloadPlugin()
        new LiveReloadPlugin(),
        // new webpack.DefinePlugin({
        //     'process.env': {'NODE_ENV': '"production"'}
        // })
    ],
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel',
                query: {
                    presets: ['es2015', 'stage-0', 'react']
                }
            }
        ]
    }
};