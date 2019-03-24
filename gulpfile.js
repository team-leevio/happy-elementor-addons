"use strict";

var autoPrefixer = require("gulp-autoprefixer"),
    browserSync = require("browser-sync"),
    concat = require("gulp-concat"),
    csscomb = require("gulp-csscomb"),
    csso = require("gulp-csso"),
    gulp = require("gulp"),
    plumberNotifier = require("gulp-plumber-notifier"),
    rename = require("gulp-rename"),
    sass = require("gulp-sass"),
    uglify = require("gulp-uglify");

/**
 * Browser selection for Autoprefixer
 * @type {Array}
 */
var AUTOPREFIXER_BROWSERS = [
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
    "bb >= 10"
];

var styleSass = "assets/dev/sass/main.scss",
    sassFiles = "assets/dev/sass/*.scss",
    jsFiles = "assets/dev/js/*.js";


gulp.task("serve", function() {
    browserSync.init({
        server: {
            baseDir: "./",
            index: ""
        },
        notify: false
    });
});

gulp.task("css", function() {
    return gulp.src(styleSass)
        .pipe(plumberNotifier())
        .pipe(sass())
        .pipe(autoPrefixer(AUTOPREFIXER_BROWSERS))
        .pipe(csscomb())
        .pipe(gulp.dest("assets/css"))
        .pipe(browserSync.stream())
        .pipe(csso())
        .pipe(rename({suffix: ".min"}))
        .pipe(gulp.dest("assets/css"));
});

gulp.task("js", function() {
    return gulp.src(jsFiles)
        .pipe(plumberNotifier())
        .pipe(concat('happy-addons.js'))
        .pipe(gulp.dest("assets/js"))
        .pipe(uglify())
        .pipe(rename({suffix: ".min"}))
        .pipe(gulp.dest("assets/js"));
});

gulp.task("watch", ["serve"], function() {
    gulp.watch(sassFiles, ["css"]);
    gulp.watch(jsFiles, ["js"]).on("change", browserSync.reload);
    gulp.watch("*.html").on("change", browserSync.reload);
});

gulp.task("default", ["serve", "js", "css", "watch"]);
