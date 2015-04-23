// ==== SCRIPTS ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , merge       = require('merge-stream')
  , config      = require('../../gulpconfig').scripts
;

var runSequence = require('run-sequence');

// Check core scripts for errors
gulp.task('scripts-lint', function() {
  return gulp.src(config.lint.src)
  .pipe(plugins.jshint('.jshintrc'))
  .pipe(plugins.jshint.reporter('default')); // No need to pipe this anywhere
});

// Minify scripts in place
gulp.task('scripts-minify', ['scripts-lib', 'scripts-lint'], function(){
  return gulp.src(config.minify.src)
  .pipe(plugins.rename(config.minify.rename))
  .pipe(plugins.uglify(config.minify.uglify))
  .pipe(gulp.dest(config.minify.dest));
});

// Minify using r.js
gulp.task('scripts-dist', function() {
    plugins.shell.task(['r.js.cmd -o build.js', 'gulp scripts-lib-dist'])();
    return gulp.src(config.lint.src)
});

// Copy library files ( e.g requirejs ) to build/js/lib
gulp.task('scripts-lib-dist', function() {
    return gulp.src(config.library.src)
        .pipe(plugins.changed(config.library.dist))
        .pipe(gulp.dest(config.library.dist));
});

// Copy library files ( e.g requirejs ) to build/js/lib
gulp.task('scripts-lib', function() {
    return gulp.src(config.library.src)
        .pipe(plugins.changed(config.library.dest))
        .pipe(gulp.dest(config.library.dest));
});

// Check core scripts for errors
gulp.task('scripts-build', ['scripts-lib'], function() {
    return gulp.src(config.build.src)
        .pipe(plugins.changed(config.build.dest))
        .pipe(gulp.dest(config.build.dest));
});


// Master script task; library -> minify using r.js
gulp.task('scripts', ['scripts-build']);

// Master script task; library -> minify using r.js
//gulp.task('scripts-dist', ['scripts-build']);
