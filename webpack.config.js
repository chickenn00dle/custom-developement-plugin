const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const { ProvidePlugin } = require( 'webpack' );
const TerserPlugin = require( 'terser-webpack-plugin' );

const NODE_ENV = process.env.NODE_ENV || 'development';

module.exports = {
	mode: NODE_ENV,
	entry: {
		index: path.resolve( __dirname, 'client', 'index.js' )
	},
	output: {
		path: path.resolve( __dirname, 'dist' ),
		filename: 'bundle.js'
	},
	module: {
		rules: [
			{
				test: /\.js?$/,
				exclude: /node_modules(\/|\\)(?!(debug))/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@wordpress/babel-preset-default' ],
					},
				},
			},
			{
				test: /\.s?css$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'sass-loader'
				]
			}
		]
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: 'main.css'
		} ),
		new ProvidePlugin( {
			process: 'process/browser'
		} )
	],
	resolve: {
		alias: {
			'plugin-settings': path.resolve(
				__dirname,
				'client/data/constants.js'
			),
		},
		fallback: {
			'path': require.resolve( 'path-browserify' )
		}
	},
	optimization: {
		minimize: NODE_ENV !== 'development',
		minimizer: [
			new TerserPlugin( {
				terserOptions: {
					format: {
						comments: false
					}
				},
				extractComments: false
			} )
		],
	}
}
