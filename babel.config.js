module.exports = function ( api ) {
	api.cache( true );

	const presets = [ '@wordpress/babel-preset-default' ];
	const plugins = [ '@babel/plugin-proposal-class-properties' ];

	return {
		presets,
		plugins
	};
}
