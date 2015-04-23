// ==== STYLES ==== //

var gulp          = require('gulp')
  , plugins       = require('gulp-load-plugins')({ camelize: true })
  , config        = require('../../gulpconfig').styles
    , replace = require('gulp-replace')
  , autoprefixer  = require('autoprefixer-core')
;


gulp.task('styles',function() {

  return gulp.src(config.build.src)
      .pipe(plugins.postcss([autoprefixer(config.autoprefixer)]))
      .pipe(gulp.dest(config.build.dest)) // Drops the unminified CSS file into the `build` folder
      .pipe(plugins.rename(config.rename))
      .pipe(plugins.minifyCss(config.minify))
      .pipe(replace('../../_system/bower/components-font-awesome/fonts', 'fonts'))
      .pipe(plugins.concat('style.min.css'))
      .pipe(gulp.dest(config.build.dest));

});


// Copy stylesheets from the `build` folder to `dist` and minify them along the way
gulp.task('styles-dist', function() {
  return gulp.src(config.dist.src)
  .pipe(plugins.minifyCss(config.minify))
  .pipe(gulp.dest(config.dist.dest));
});
