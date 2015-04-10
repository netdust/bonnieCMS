// ==== THEME ==== //

var gulp        = require('gulp')
    , plugins     = require('gulp-load-plugins')({ camelize: true })
    , config      = require('../../gulpconfig').composer
    ;



// Copy PHP composer source files to the `build` folder
gulp.task('composer', function() {
    return gulp.src(config.src)
        .pipe(plugins.changed(config.dest))
        .pipe(gulp.dest(config.dest));
});


