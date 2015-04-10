// ==== THEME ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../../gulpconfig').theme
;

gulp.task('theme-dev', ['theme-files'], function() { // make sure vendor files are copied too
    gulp.watch(config.src, ['theme-files']);
});

gulp.task('theme-files', function() { // make sure vendor files are copied too
    return gulp.src(config.src)
        .pipe(plugins.changed(config.dest))
        .pipe(gulp.dest(config.dest));
});

// All the theme tasks in one
gulp.task('theme', ['theme-files']);
