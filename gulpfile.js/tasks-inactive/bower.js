// ==== BOWER ==== //

var gulp        = require('gulp')
  , plugins     = require('gulp-load-plugins')({ camelize: true })
  , config      = require('../../gulpconfig').bower

    //not recognized by load-plugins
  , mainBowerFiles  = require('main-bower-files')
;

// This task is executed on `bower update` which is in turn triggered by `npm update`; use this to copy and transform source files managed by Bower
gulp.task('bower', ['bower-move']);

// little helper function to filter files with certain extension
var filterByExtension = function(extension){
    return plugins.filter(function(file){
        return file.path.match(new RegExp('.' + extension + '$'));
    });
};

gulp.task('bower-update', function(cb){
    bower.commands.install([],{save:true},{})
    .on('end', function(installed){
        cb();
    });
});
gulp.task('bower-move', function(){
    var mainFiles = mainBowerFiles();
    var jsFilter = filterByExtension('js');
    return gulp.src(mainFiles)
        .pipe(jsFilter)
        .pipe(plugins.concat(config.move.js.rename))
        .pipe(gulp.dest(config.move.js.dest))
        .pipe(jsFilter.restore())
        .pipe(filterByExtension('css'))
        .pipe(plugins.concat(config.move.css.dest))
        .pipe(gulp.dest(config.move.css.dest));
});