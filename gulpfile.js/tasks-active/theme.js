// ==== THEME ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../../gulpconfig').theme
;

gulp.task('theme-build', function() { // make sure vendor files are copied too
    return gulp.src(config.build.src)
        .pipe(plugins.changed(config.build.dest))
        .pipe(gulp.dest(config.build.dest));
});

gulp.task('theme-dist', function() {
    return gulp.src(config.dist.src)
        .pipe(plugins.changed(config.dist.dest))
        .pipe(gulp.dest(config.dist.dest));
});

gulp.task('theme', ['theme-build']);