import { readFileSync } from 'fs';
import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as dartSass from 'sass';
import gulpCsso from 'gulp-csso';
import gulpRename from 'gulp-rename';
import gulpBabel from 'gulp-babel';
import gulpUglify from 'gulp-uglify';
import gulpCsscomb from 'gulp-csscomb';
import gulpAutoPrefixer from 'gulp-autoprefixer';
import gulpPlumberNotifier from 'gulp-plumber-notifier';
import gulpConcat from 'gulp-concat';
import gulpClean from 'gulp-clean';
import gulpZip from 'gulp-zip';

const packageJSON = JSON.parse( readFileSync( new URL( './package.json', import.meta.url ) ) );
const { src, watch, dest, series } = gulp;
const sass = gulpSass( dartSass );

const AUTOPREFIXER_BROWSERS = [
	"last 2 version",
	"> 1%",
	"ie >= 9",
	"ie_mob >= 10",
	"ff >= 30",
	"chrome >= 34",
	"safari >= 7",
	"opera >= 23",
	"ios >= 7",
	"android >= 4",
	"bb >= 10",
];

const frontendSassFiles = "assets/dev/sass/**/*.scss";
const backendSassFiles = "assets/dev/admin/sass/**/*.scss";
const frontendJSFiles = "assets/dev/js/**/*.js";
const backendJSFiles = "assets/dev/admin/js/**/*.js";

const packageName = packageJSON.name;
const packageVersion = packageJSON.version;

const buildSrcFiles = [
	"./**/*",
	"!./**/_*/",
	"!./node_modules/**",
	"!./.csscomb.json",
	"!./.distignore",
	"!./.editorconfig",
	"!./.vscode/**",
	"!./.gitattributes",
	"!./.gitignore",
	"!./assets/dev/**",
	"!./package-lock.json",
	"!./package.json",
	"!./gulpfile.js",
	"!./yarn.lock",
	"!./README.md",
	// "./vendor/**/*",
	"!./composer.lock",
	"!./vendor/bin/**",
	"!./vendor/composer/**",
	"!./vendor/dealerdirect/**",
	"!./vendor/phpcsstandards/**",
	"!./vendor/squizlabs/**",
	"!./vendor/wp-coding-standards/**",
	"!./vendor/autoload.php",
];

function makeFrontendCSS () {
	return src( frontendSassFiles )
		.pipe( gulpPlumberNotifier() )
		.pipe( sass() )
		.pipe( gulpAutoPrefixer( AUTOPREFIXER_BROWSERS ) )
		.pipe( gulpCsscomb() )
		.pipe( gulpCsso() )
		.pipe( gulpRename( { suffix: ".min" } ) )
		.pipe( dest( "assets/css/widgets" ) )
		.pipe( gulpConcat( "main.css" ) )
		.pipe( dest( "assets/css" ) )
		.pipe( gulpRename( { suffix: ".min" } ) )
		.pipe( dest( "assets/css" ) );
}

function makeBackendCSS () {
	return src( backendSassFiles )
		.pipe( gulpPlumberNotifier() )
		.pipe( sass() )
		.pipe( gulpAutoPrefixer( AUTOPREFIXER_BROWSERS ) )
		.pipe( gulpCsscomb() )
		.pipe( dest( "assets/admin/css" ) )
		.pipe( gulpCsso() )
		.pipe( gulpRename( { suffix: ".min" } ) )
		.pipe( dest( "assets/admin/css" ) );
}

function makeFrontendJS () {
	return (
		src( frontendJSFiles )
			.pipe( gulpPlumberNotifier() )
			.pipe(
				gulpBabel( {
					presets: [ "@babel/env" ],
				} )
			)
			.pipe( dest( "assets/js" ) )
			.pipe( gulpUglify() )
			.pipe( gulpRename( { suffix: ".min" } ) )
			.pipe( dest( "assets/js" ) )
	);
}

function makeBackendJS () {
	return src( backendJSFiles )
		.pipe( gulpPlumberNotifier() )
		.pipe(
			gulpBabel( {
				presets: [ "@babel/env" ],
			} )
		)
		.pipe( dest( "assets/admin/js" ) )
		.pipe( gulpUglify() )
		.pipe( gulpRename( { suffix: ".min" } ) )
		.pipe( dest( "assets/admin/js" ) )
		.on( "error", swallowError );
}

function swallowError ( error ) {
	// If you want details of the error in the console
	console.log( error.toString() );

	this.emit( "end" );
}

function startWatching () {
	watch( frontendSassFiles, makeFrontendCSS );
	watch( backendSassFiles, makeBackendCSS );
	watch( frontendJSFiles, makeFrontendJS );
	watch( backendJSFiles, makeBackendJS );
}

function deleteOld () {
	return src( [ "assets/css", "assets/admin", "assets/js" ], {
		read: false,
	} ).pipe( gulpClean( { force: true } ) );
}

function buildZip () {
	return src( buildSrcFiles, { base: "./" } )
		.pipe(
			gulpRename( function ( file ) {
				file.dirname = packageName + "/" + file.dirname;
			} )
		)
		.pipe( gulpZip( packageName + "-v" + packageVersion + ".zip" ) )
		.pipe( dest( "../" ) );
}

function buildRelease () {
	return src( buildSrcFiles ).pipe( dest( "../happy-elementor-addons-build" ) );
}

function deleteBuild () {
	return src( [ "../happy-elementor-addons-build" ], {
		read: false,
		allowEmpty: true,
	} ).pipe( gulpClean( { force: true } ) );
}

function deleteZip () {
	return src( [ "../happy-elementor-addons.zip" ], {
		read: false,
		allowEmpty: true,
	} ).pipe( gulpClean( { force: true } ) );
}

export const build = series(
	makeFrontendCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeBackendJS,
	deleteBuild,
	buildRelease
);

export const zip = series(
	makeFrontendCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeBackendJS,
	deleteZip,
	buildZip
);

export const clean = deleteOld;

export const production = series(
	makeFrontendCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeBackendJS
);

export default series(
	makeFrontendCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeBackendJS,
	startWatching
);
