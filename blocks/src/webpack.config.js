const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		'review-list': path.resolve(__dirname, 'src/review-list/index.js'),
		'review-form': path.resolve(__dirname, 'src/review-form/index.js'),
	},
	output: {
		path: path.resolve(__dirname, 'build'),
	},
};
