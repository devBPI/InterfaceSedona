// Retrieve gulp functions
const { series, parallel, src, dest, task, watch } = require('gulp');

// Load gulp modules
const babelify      = require('babelify');
const browserify    = require('browserify');
const minimist      = require('minimist');
const buffer        = require('vinyl-buffer');

const autoprefixer  = require('gulp-autoprefixer');
const cleanCSS      = require('gulp-clean-css');
const cleanfix      = require('gulp-clean-fix');
const concat        = require('gulp-concat');
const csslint       = require('gulp-csslint');
const header        = require('gulp-header');
const jshint        = require('gulp-jshint');
const less          = require('gulp-less');
const lesshint      = require('gulp-lesshint');
const livereload    = require('gulp-livereload');
const rename        = require('gulp-rename');
const tap           = require('gulp-tap');
const uglify        = require('gulp-uglify');

// Globals paths
const assetsPath    = './assets/';
const publicPath    = './public/';
const modPath    = './node_modules/';

// Array of paths
const paths = {
    // -- LESS --
    lessSource:     assetsPath + 'less',
    lessDest:       assetsPath + 'css/build',
    // -- CSS --
    cssLib:         [
        // modPath + 'bootstrap/dist/css/*.min.css',                                   // css bootstrap
        // modPath + 'bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css',    // bootstrap-datepicker'
        // modPath + '@fortawesome/fontawesome-free/css/all.css',                      // css fontawesome
        // modPath + 'toastr/build/toastr.min.css',                                    // css toastr
        // modPath + 'cropper/dist/cropper.min.css',                                   // css cropper
        // modPath + 'slick-carousel/slick/slick-theme.css',                        // plugin pour le caroussel
        assetsPath + 'plugin/**/*.css',                                             // css in assets/plugin
        publicPath + 'bundles/*/css/*.css',                                         // css in public/bundles
    ],
    cssSource:      assetsPath + 'css',
    // cssBuild:       assetsPath + 'build/css',
    cssDest:        publicPath + 'dist/css',
    // -- JS --
    jsLib:          [
        // modPath + 'jquery/dist/jquery.min.js',                                      // jquery
        // modPath + 'popper.js/dist/umd/popper.min.js',                               // popper.js
        // modPath + 'bootstrap/dist/js/*.min.js',                                     // js bootstrap
        // modPath + 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',       // bootstrap-datepicker
        // modPath + 'bootstrap-datepicker/dist/locales/bootstrap-datepicker.fr.min.js',
        // modPath + 'slick-carousel/slick/slick.min.js',                           // plugin pour le caroussel
        // modPath + 'popper.js/dist/umd/popper-utils.min.js',                      // popper.js utils
        // modPath + 'moment/min/moment.min.js',                                       // moment librairie de gestion de date
        // modPath + 'moment/locale/fr.js',                                            // moment locale fr
        // modPath + 'toastr/build/toastr.min.js',                                     // toastr librairie de notification
        // modPath + 'cropper/dist/cropper.min.js',                                    // cropper librairie de manipulation d'image
        // modPath + 'html2canvas/dist/html2canvas.min.js',                            // html2canvas
        // modPath + 'jspdf/dist/jspdf.min.js',                                        // html2pdf
        assetsPath + 'plugin/**/*.js',                                              // js in assets/plugin
        publicPath + 'bundles/*/js/*.js',                                           // js in public/bundles
    ],
    jsSource:       assetsPath + 'js',
    // jsBuild:        assetsPath + 'build/js',
    jsDest:         publicPath + 'dist/js',
    // -- FONTS --
    fontDest:       publicPath + 'dist/webfonts',
    fontLib:       [
        modPath + '@fortawesome/fontawesome-free/webfonts/*',          // fontawesome
        modPath + 'slick-carousel/slick/fonts/*',                      // plugin pour le caroussel
        assetsPath + 'font/*',
    ]
};

// Command Options
var knownOptions = {
    string: ['env', 'lint'],
    default: {
        env: process.env.NODE_ENV || 'development',
        lint: false
    }
};

// Parse options string from command line
var options = minimist(process.argv.slice(2), knownOptions);


// Clean folders containing generated files
function clean() {
    return src(paths.lessDest + '/**/*.css', {read: false, allowEmpty: true})
    // .pipe(src(paths.cssBuild + '/**/*.css', {read: false, allowEmpty: true}))
    // .pipe(src(paths.jsBuild + '/**/*.js', {read: false, allowEmpty: true}))
        .pipe(src(paths.cssDest + '/**/*.css', {read: false, allowEmpty: true}))
        .pipe(src(paths.jsDest + '/**/*.js', {read: false, allowEmpty: true}))
        .pipe(cleanfix());
}


// LESS lint
function lessLint() {
    return src(paths.lessSource + '/**/*.less')
        .pipe(lesshint({
            // Options
        }))
        .pipe(lesshint.reporter()) // Leave empty to use the default, "stylish"
        .pipe(lesshint.failOnError()); // Use this to fail the task on lint errors
}

// LESS to CSS
function lessTranspile() {
    return src(paths.lessSource + '/**/*.less')
        .pipe(less())
        // .pipe(less({
        //     paths: [ paths.lessIncludes ]  // paths: Array of paths to be used for @import directives
        //     plugins: [myplugin] // plugins: Array of less plugins (details)
        // }))
        .pipe(dest(paths.lessDest));
}

// CSS lint
function cssLint() {
    return src(paths.cssSource + '/**/*.css')
        .pipe(csslint())
        .pipe(csslint.formatter());
    // .pipe(csslint.formatter('fail')); // Set formater to fail on error
}

// CSS autoprefix + concat
function cssTranspile() {
    return src(paths.cssSource + '/**/*.css')
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(concat('theme.css'))
        // .pipe(header('/* Updated: ' + Date.now() + ' */\n')) // add updated_at date on top of result file
        .pipe(dest(paths.cssDest));
}

// CSS minify
function cssMinify() {
    return src(paths.cssDest + '/**/*.css')
        .pipe(cleanCSS({compatibility: 'ie10'}))
        // .pipe(rename({ extname: '.min.css' }))
        .pipe(dest(paths.cssDest));
}

// Concat CSS lib
function cssBundle() {
    return src(paths.cssLib)
        .pipe(concat('lib.css'))
        .pipe(dest(paths.cssDest));
}

// JS lint
function jsLint() {
    return src(paths.jsSource + '/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter()); // log error on console 'default', 'jshint-stylish'
    // .pipe(jshint.reporter('fail')); // fail on error
}

// JS transpile (babel + browserify) > one entrypoint per page
function jsTranspile()
{
    return src(paths.jsSource + '/*.js')
        .pipe(tap(function (file) { // foreach file (= js page entry-point)
            // log.info('bundling ' + file.path);

            // browserify and babelify stream
            file.contents = browserify(file.path, {debug: true})
                .transform(babelify.configure({
                    presets: ["@babel/preset-env"],
                    ignore: [/(bower_components)|(node_modules)/]
                }))
                .bundle();
        }))
        .pipe(buffer())
        .pipe(dest(paths.jsDest));
}

// JS Minify app files
function jsMinify() {
    return src(paths.jsDest + '/**/*.js')
        .pipe(uglify())
        // .pipe(rename({ extname: '.min.js' }))
        .pipe(dest(paths.jsDest));
}

// JS concat libraries
function jsBundle() {
    return src(paths.jsLib)
        .pipe(concat('lib.js'))
        .pipe(dest(paths.jsDest));
}

// Watch file changes to regenerate files
function watchReload() {
    livereload.listen();

    // LESS Watch
    watch(paths.lessSource + '/**/*.less', series(lessLint, lessTranspile, cssTranspile));

    // CSS Watch
    watch(paths.cssSource + '/**/*.css', series(cssLint, cssTranspile));

    // JS Watch
    watch(paths.jsSource + '/**/*.js', series(jsLint, jsTranspile));
}


// JS concat libraries
function jsBundle() {
    return src(paths.jsLib)
        .pipe(concat('lib.js'))
        .pipe(dest(paths.jsDest));
}


// FONTS
function fontCopy() {
    return src(paths.fontLib)
        .pipe(dest(paths.fontDest));
}

var build;
var lint            = parallel(lessLint, cssLint, jsLint);
var buildDev        = parallel(
    series(lessTranspile, cssTranspile),
    jsTranspile,
    cssBundle,
    jsBundle,
    fontCopy);
var buildTest       = parallel(
    series(lessTranspile, cssTranspile, cssBundle, cssMinify),
    series(jsTranspile, jsBundle, jsMinify),
    fontCopy);
var buildProd       = parallel(
    series(lessTranspile, cssTranspile, cssBundle, cssMinify),
    series(jsTranspile, jsBundle, jsMinify),
    fontCopy);

// Create build task based on 'env' value
switch (options.env) {
    case 'prod':
    case 'production':
        build = series(
            clean,
            buildProd);
        break;

    case 'test':
        if(options.lint === true) {
            build = series(
                lint,
                clean,
                buildTest);
        } else {
            build = series(
                clean,
                buildTest);
        }
        break;

    case 'dev':
    case 'development':
    default:
        if(options.lint === true) {
            build = series(
                lint,
                clean,
                buildDev);
        } else {
            build = series(
                clean,
                buildDev);
        }

        break;
}

// Export functions as task (= can be called with command line)
// # gulp [command]
exports.clean       = clean;
exports.lessLint    = lessLint;
exports.cssLint     = cssLint;
exports.jsLint      = jsLint;
exports.lint        = lint;
exports.build       = build;
exports.watch       = series(build, watchReload);
exports.default     = build;

// to set env from command line :
// gulp --env [dev|test|prod] [command]

// to disable lint from command line :
// gulp --lint false [command]

// list tasks
// gulp --tasks
