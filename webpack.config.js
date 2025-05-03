const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const devHost = 'wpmvc.test';

module.exports = {
	...defaultConfig,
	entry: {
		'dashboard-app': './resources/js/dashboard/DashboardApp.js',
	},
	resolve: {
		alias: {
			'@DashboardApp': path.resolve( __dirname, 'resources/js/dashboard/dashboard-app/' ),
			'@Admin': path.resolve( __dirname, 'resources/js/dashboard/' ),
			'@Utils': path.resolve( __dirname, 'resources/js/dashboard/utils/' ),
			'@Skeleton': path.resolve( __dirname, 'resources/js/dashboard/common/skeleton/' ),
			'@Common': path.resolve( __dirname, 'resources/js/dashboard/common/' ),
			'@helpers': path.resolve( __dirname, 'resources/js/helpers' ),
		},
	},
	output: {
		path: path.resolve( __dirname, './assets/build/' ),
		filename: '[name].js',
		clean: false,
	},
	devServer: {
		devMiddleware: {
			writeToDisk: true,
		},
		allowedHosts: 'auto',
		port: 8887,
		host: devHost,
		proxy: {
			'/assets/build': {
				pathRewrite: {
					'^/assets/build': '',
				},
			},
		},
		headers: { 'Access-Control-Allow-Origin': '*' },
	}
};
