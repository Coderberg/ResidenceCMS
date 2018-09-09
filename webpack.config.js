const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const fs = require('fs');

module.exports = {
  entry: [
    './assets/js/app.js',
    './assets/scss/app.scss'
  ],
  output: {
    filename: 'js/app.js',
	path: path.resolve(__dirname, 'public')
  },
  devtool: "source-map",
  module: {
    rules: [{
        test: /\.js$/,
        include: path.resolve(__dirname, 'assets/js'),
        use: {
          loader: 'babel-loader',
          options: {
            presets: 'env'
          }
        }
      },
      {
        test: /\.(css|sass|scss)$/,
        include: path.resolve(__dirname, 'assets/scss'),
        use: ExtractTextPlugin.extract({
          use: [{
              loader: "css-loader",
              options: {
                sourceMap: true,
                minimize: true,
                url: false
              }
            }, 
			{
			  loader: 'postcss-loader', // Run post css actions
			  options: {
				plugins: function () { // post css plugins, can be exported to postcss.config.js
				  return [
					require('precss'),
					require('autoprefixer')
				  ];
				}
			  }
			},
            {
              loader: "sass-loader",
              options: {
                sourceMap: true
              }
            }
          ]
        })
      },
    ]
  },
  plugins: [
    new ExtractTextPlugin({
      filename: 'css/app.css',
      allChunks: true,
    }),
  ]
};