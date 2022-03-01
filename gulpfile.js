const Gulp = require('gulp');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const sass = require('gulp-sass');


Gulp.task('sass', () => {
  const plugins = [
    autoprefixer({ browsers: ['IE >= 10', 'ie >= 10', 'last 2 version'] })
    // cssnano()
  ];

  return Gulp.src('./scss/style.scss')
             .pipe(sass().on('error', sass.logError))
             .pipe(postcss(plugins))
             .pipe(Gulp.dest('./'));
});

Gulp.task('sass:watch', () => {
  Gulp.watch('./scss/**/*.scss', Gulp.series(['sass']));
});

Gulp.task('default', Gulp.series(['sass']));
