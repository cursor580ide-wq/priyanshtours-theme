const gulp = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const cleanCSS = require('gulp-clean-css');

// Define paths
const paths = {
  styles: {
    src: [
      './style.css',
      './assets/css/**/*.css'
    ],
    dest: './dist/css/'
  },
  scripts: {
    src: './assets/js/**/*.js',
    dest: './dist/js/'
  }
};

// CSS task: concat and minify
function styles() {
  return gulp.src(paths.styles.src)
    .pipe(concat('main.min.css'))
    .pipe(cleanCSS())
    .pipe(gulp.dest(paths.styles.dest));
}

// JS task: concat and uglify
function scripts() {
  return gulp.src(paths.scripts.src, { sourcemaps: true })
    .pipe(concat('main.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(paths.scripts.dest));
}

// Watch task
function watch() {
  gulp.watch(paths.styles.src, styles);
  gulp.watch(paths.scripts.src, scripts);
}

// Define complex tasks
const build = gulp.parallel(styles, scripts);

// Export tasks
exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
exports.build = build;
exports.default = build;
