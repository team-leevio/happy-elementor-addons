const packageJSON = require("./package.json");
const { src, watch, dest, series } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const csso = require("gulp-csso");
const rename = require("gulp-rename");
const babel = require("gulp-babel");
const uglify = require("gulp-uglify");
const csscomb = require("gulp-csscomb");
const autoPrefixer = require("gulp-autoprefixer");
const plumberNotifier = require("gulp-plumber-notifier");
const concat = require("gulp-concat");
const clean = require("gulp-clean");
const wpPot = require("gulp-wp-pot");
const zip = require("gulp-zip");

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
];

function makeFrontendCSS() {
    return src(frontendSassFiles)
        .pipe(plumberNotifier())
        .pipe(sass())
        .pipe(autoPrefixer(AUTOPREFIXER_BROWSERS))
        .pipe(csscomb())
        .pipe(csso())
        .pipe(rename({ suffix: ".min" }))
        .pipe(dest("assets/css/widgets"))
        .pipe(concat("main.css"))
        .pipe(dest("assets/css"))
        .pipe(rename({ suffix: ".min" }))
        .pipe(dest("assets/css"));
}

function makeBackendCSS() {
    return src(backendSassFiles)
        .pipe(plumberNotifier())
        .pipe(sass())
        .pipe(autoPrefixer(AUTOPREFIXER_BROWSERS))
        .pipe(csscomb())
        .pipe(dest("assets/admin/css"))
        .pipe(csso())
        .pipe(rename({ suffix: ".min" }))
        .pipe(dest("assets/admin/css"));
}

function makeFrontendJS() {
    return (
        src(frontendJSFiles)
        .pipe(plumberNotifier())
        .pipe(
            babel({
                presets: ["@babel/env"],
            })
        )
        .pipe(dest("assets/js"))
        //.pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(dest("assets/js"))
    );
}

function makeBackendJS() {
    return src(backendJSFiles)
        .pipe(plumberNotifier())
        .pipe(
            babel({
                presets: ["@babel/env"],
            })
        )
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(dest("assets/admin/js"))
        .on("error", swallowError);
}

function swallowError(error) {
    // If you want details of the error in the console
    console.log(error.toString());

    this.emit("end");
}

function startWatching() {
    watch(frontendSassFiles, makeFrontendCSS);
    watch(backendSassFiles, makeBackendCSS);
    watch(frontendJSFiles, makeFrontendJS);
    watch(backendJSFiles, makeBackendJS);
}

function deleteOld() {
    return src(["assets/css", "assets/admin", "assets/js"], {
        read: false,
    }).pipe(clean({ force: true }));
}

function makePot() {
    return src(["./*.php", "./**/*.php"])
        .pipe(
            wpPot({
                domain: "happy-elementor-addons",
                package: "Happy Addons",
                team: "HappyMonster <happyaddons@gmail.com>",
            })
        )
        .pipe(dest("i18n/happy-elementor-addons.pot"));
}

function buildZip() {
    return src(buildSrcFiles, { base: "./" })
        .pipe(
            rename(function(file) {
                file.dirname = packageName + "/" + file.dirname;
            })
        )
        .pipe(zip(packageName + "-v" + packageVersion + ".zip"))
        .pipe(dest("../"));
}

function buildRelease() {
    return src(buildSrcFiles).pipe(dest("../happy-elementor-addons-build"));
}

function deleteBuild() {
    return src(["../happy-elementor-addons-build"], {
        read: false,
        allowEmpty: true,
    }).pipe(clean({ force: true }));
}

function deleteZip() {
    return src(["../happy-elementor-addons.zip"], {
        read: false,
        allowEmpty: true,
    }).pipe(clean({ force: true }));
}

exports.build = series(
    makeFrontendCSS,
    makeBackendCSS,
    makeFrontendJS,
    makeBackendJS,
    deleteBuild,
    buildRelease
);

exports.zip = series(
    makeFrontendCSS,
    makeBackendCSS,
    makeFrontendJS,
    makeBackendJS,
    deleteZip,
    buildZip
);

exports.pot = makePot;
exports.clean = deleteOld;

exports.production = series(
    makeFrontendCSS,
    makeBackendCSS,
    makeFrontendJS,
    makeBackendJS
);

exports.default = series(
    makeFrontendCSS,
    makeBackendCSS,
    makeFrontendJS,
    makeBackendJS,
    startWatching
);
