// ==== MAIN ==== //

var gulp        = require('gulp')
    , plugins   = require('gulp-load-plugins')({ camelize: true });

var runSequence = require('run-sequence');

// Default task chain: build -> (livereload or browsersync) -> watch
gulp.task('default', ['watch']);

// Copy vendor files and build copy
gulp.task('init', ['composer', 'build'] );

// Build a working copy of the theme
gulp.task('build', ['images', 'fonts', 'scripts', 'styles', 'theme', 'composer']);

// Dist task chain: wipe -> build -> clean -> copy -> images/styles
// NOTE: this is a resource-intensive task!

gulp.task('dist', function() {
    runSequence('utils-dist', 'images-dist', 'styles-dist', 'theme-dist', 'scripts-dist');
});
