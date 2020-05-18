const pkg = require('./package.json');

const banner = `/*
 * @license
 * ` + pkg.name + `
 * https://github.com/nagix/chartjs-plugin-style/
 * Version: ` + pkg.version + `
 *
 * Copyright ` + (new Date().getFullYear()) + ` Akihiko Kusanagi
 * Released under the MIT license
 * https://github.com/nagix/` + pkg.name + `/blob/master/LICENSE.md
 */`;

export default {
	input: 'src/index.js',
	output: 'dist/' + pkg.name + '.js',
	banner: banner,
	format: 'umd',
	name: pkg.name,
	external: [
		'chart.js'
	],
	globals: {
		'chart.js': 'Chart'
	}
};
