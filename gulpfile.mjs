import { readFileSync } from "fs";
import gulp from "gulp";
import * as sass from "sass";
import gulpCsso from "gulp-csso";
import gulpRename from "gulp-rename";
import gulpBabel from "gulp-babel";
import gulpUglify from "gulp-uglify";
import gulpCsscomb from "gulp-csscomb";
import gulpAutoPrefixer from "gulp-autoprefixer";
import gulpPlumberNotifier from "gulp-plumber-notifier";
import gulpConcat from "gulp-concat";
import gulpClean from "gulp-clean";
import gulpZip from "gulp-zip";
import fs from "fs";
import through from "through2";
import path from "path";

const packageJSON = JSON.parse(
	readFileSync(new URL("./package.json", import.meta.url)),
);
const { src, watch, dest, series } = gulp;

// Custom Sass compiler using the modern Sass API
function compileSass() {
	return through.obj(function (file, enc, cb) {
		if (file.isNull()) {
			return cb(null, file);
		}

		if (file.isStream()) {
			return cb(new Error("Streaming not supported"));
		}

		try {
			// Use the modern Sass API
			const result = sass.compile(file.path, {
				style: "expanded",
				// Suppress deprecation warnings
				verbose: false,
			});

			file.contents = Buffer.from(result.css);

			// Update the extension to .css
			file.path = file.path.replace(path.extname(file.path), ".css");

			cb(null, file);
		} catch (err) {
			cb(err);
		}
	});
}

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
const atomicJSDir = "assets/dev/js/atomic/";
// Elementor V4 (Atomic) scripts are source modules, not standalone files: they
// are concatenated in this order into one runtime bundle (the first opens the
// closure, the last closes it), and the editor script is built on its own.
const atomicRuntimeJSFiles = [
	"core",
	"image-cycle",
	"appearing-image-animation",
	"text-animation",
	"lifecycle",
].map((name) => atomicJSDir + name + ".js");
const atomicEditorJSFile = atomicJSDir + "editor.js";
const frontendJSFiles = ["assets/dev/js/**/*.js", "!" + atomicJSDir + "**"];
const backendJSFiles = "assets/dev/admin/js/**/*.js";

const packageName = packageJSON.name;
const packageVersion = packageJSON.version;

const buildSrcFiles = [
	"./**/*",
	"!./dist/*",
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
	"!./gulpfile.mjs",
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
	"!./packer.sh",
	"!./ARCHITECTURE_OVERVIEW.md"
];

// Elementor V4 (Atomic) styles are loaded per component, so they are kept
// out of the main.css bundle (see makeAtomicCSS, which skips csscomb because
// it cannot parse :where()/:is() selector lists).
const atomicSassFiles = "assets/dev/sass/atomic/**/*.scss";

function makeFrontendCSS() {
	return src([frontendSassFiles, "!" + atomicSassFiles])
		.pipe(gulpPlumberNotifier())
		.pipe(compileSass())
		.pipe(gulpAutoPrefixer(AUTOPREFIXER_BROWSERS))
		.pipe(gulpCsscomb())
		.pipe(gulpCsso())
		.pipe(gulpRename({ suffix: ".min" }))
		.pipe(dest("assets/css/widgets"))
		.pipe(gulpConcat("main.css"))
		.pipe(dest("assets/css"))
		.pipe(gulpRename({ suffix: ".min" }))
		.pipe(dest("assets/css"));
}

function makeAtomicCSS() {
	return src(atomicSassFiles)
		.pipe(gulpPlumberNotifier())
		.pipe(compileSass())
		.pipe(gulpAutoPrefixer(AUTOPREFIXER_BROWSERS))
		// Unminified copy too: Atomic_Manager::register_assets() asks for it while SCRIPT_DEBUG is on.
		.pipe(dest("assets/css/widgets/atomic"))
		.pipe(gulpCsso())
		.pipe(gulpRename({ suffix: ".min" }))
		.pipe(dest("assets/css/widgets/atomic"));
}

function makeBackendCSS() {
	return src(backendSassFiles)
		.pipe(gulpPlumberNotifier())
		.pipe(compileSass())
		.pipe(gulpAutoPrefixer(AUTOPREFIXER_BROWSERS))
		.pipe(gulpCsscomb())
		.pipe(dest("assets/admin/css"))
		.pipe(gulpCsso())
		.pipe(gulpRename({ suffix: ".min" }))
		.pipe(dest("assets/admin/css"));
}

function makeFrontendJS() {
	return src(frontendJSFiles)
		.pipe(gulpPlumberNotifier())
		.pipe(
			gulpBabel({
				presets: ["@babel/env"],
			}),
		)
		.pipe(dest("assets/js"))
		.pipe(gulpUglify())
		.pipe(gulpRename({ suffix: ".min" }))
		.pipe(dest("assets/js"));
}

function minifyJS(stream) {
	return stream
		.pipe(
			gulpBabel({
				presets: ["@babel/env"],
			}),
		)
		.pipe(dest("assets/js"))
		.pipe(gulpUglify())
		.pipe(gulpRename({ suffix: ".min" }))
		.pipe(dest("assets/js"));
}

function makeAtomicRuntimeJS() {
	return minifyJS(
		src(atomicRuntimeJSFiles)
			.pipe(gulpPlumberNotifier())
			.pipe(gulpConcat("happy-addons-atomic.js")),
	);
}

function makeAtomicEditorJS() {
	return minifyJS(
		src(atomicEditorJSFile)
			.pipe(gulpPlumberNotifier())
			.pipe(gulpRename("happy-atomic-editor.js")),
	);
}

const makeAtomicJS = series(makeAtomicRuntimeJS, makeAtomicEditorJS);

function makeBackendJS() {
	return src(backendJSFiles)
		.pipe(gulpPlumberNotifier())
		.pipe(
			gulpBabel({
				presets: ["@babel/env"],
			}),
		)
		.pipe(dest("assets/admin/js"))
		.pipe(gulpUglify())
		.pipe(gulpRename({ suffix: ".min" }))
		.pipe(dest("assets/admin/js"))
		.on("error", swallowError);
}

function swallowError(error) {
	// If you want details of the error in the console
	console.log(error.toString());

	this.emit("end");
}

function startWatching() {
	watch(frontendSassFiles, series(makeFrontendCSS, makeAtomicCSS));
	watch(backendSassFiles, makeBackendCSS);
	watch(frontendJSFiles, makeFrontendJS);
	watch(atomicJSDir + "**/*.js", makeAtomicJS);
	watch(backendJSFiles, makeBackendJS);
}

function deleteOld__old() {
	return src(["assets/css", "assets/admin", "assets/js"], {
		read: false,
	}).pipe(gulpClean({ force: true }));
}

function deleteOld() {
	const paths = ["assets/css", "assets/admin", "assets/js"];

	// Filter paths that exist
	const existingPaths = paths.filter((path) => fs.existsSync(path));

	if (existingPaths.length > 0) {
		return src(existingPaths, {
			read: false,
		}).pipe(gulpClean({ force: true }));
	} else {
		console.log("No paths exist to clean.");
		return Promise.resolve(); // Return a resolved promise to avoid breaking the Gulp task chain
	}
}

function buildZip() {
	return src(buildSrcFiles, { base: "./" })
		.pipe(
			gulpRename(function (file) {
				file.dirname = packageName + "/" + file.dirname;
			}),
		)
		.pipe(gulpZip(packageName + "-v" + packageVersion + ".zip"))
		.pipe(dest("./dist/"));
}

function buildRelease() {
	return src(buildSrcFiles).pipe(dest("./dist/build"));
}

function deleteBuild() {
	return src(["./dist"], {
		read: false,
		allowEmpty: true,
	}).pipe(gulpClean({ force: true }));
}

export const build = series(
	deleteBuild,
	deleteOld,
	makeFrontendCSS,
	makeAtomicCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeAtomicJS,
	makeBackendJS,
	buildRelease,
);

export const zip = series(
	deleteBuild,
	deleteOld,
	makeFrontendCSS,
	makeAtomicCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeAtomicJS,
	makeBackendJS,
	buildZip,
);

export const clean = deleteOld;

export const production = series(
	makeFrontendCSS,
	makeAtomicCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeAtomicJS,
	makeBackendJS,
);

export default series(
	makeFrontendCSS,
	makeAtomicCSS,
	makeBackendCSS,
	makeFrontendJS,
	makeAtomicJS,
	makeBackendJS,
	startWatching,
);
