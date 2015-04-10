// ==== CONFIGURATION ==== //

// Project paths
var project     = 'netcms'
  , src         = './src/'
  , build       = './build/'
  , dist        = './dist/'+project+'/'
  , bower       = './src/_system/bower/'
  , composer    = './src/_system/vendor/'
;

// Project settings
module.exports = {

    scripts: {
        library: {
            src: [bower+'requirejs/require.js']
            , dest: build+'admin/js/lib/'
        }
        , build: {
            src: [src + 'www/admin/js/**/*.js']
            , dest: build+'admin/js/lib/'
        }
        , lint: {
            src: [src + 'www/admin/js/**/*.js'] // Lint core scripts (for everything else we're relying on the original authors)
        }
    },

    styles: {
        build: {
            src: src+'www/admin/css/**/*.css'
            , dest: build+"admin/css"
        }
        , dist: {
            src: [build+'admin/css/**/*.css', '!'+dist+'admin/css/**/*.min.css']
            , minify: { keepSpecialComments: 1, roundingPrecision: 3 }
            , dest: dist+"admin/css/"
        }
        , autoprefixer: { browsers: ['> 3%', 'last 2 versions', 'ie 9', 'ios 6', 'android 4'] }
        , rename: { suffix: '.min' }
        , minify: { keepSpecialComments: 1, roundingPrecision: 3, relativeTo:src }
    },

    images: {
        build: { // Copies images from `src` to `build`; does not optimize
            src: [src+'www/**/*(*.png|*.jpg|*.jpeg|*.gif)']
            , dest: build
        }
        , dist: {
            src: [build+'**/*(*.png|*.jpg|*.jpeg|*.gif)']
            , imagemin: {
                optimizationLevel: 7
                , progressive: true
                , interlaced: true
            }
            , dest: dist
        }
    }

    , composer: {
        src: [composer+'**/*.php', '!'+composer+'**/docs/**', '!'+composer+'**/test/**', '!'+composer+'**/tests/**' ]
        , dest: build+'system/vendor/'
    }

    , theme: {
        src:[src+'www/**/*(*.html|*.tpl|*.php|*.eot|*.woff|*.oft|*.svg)', '!'+src+'www/public/**/*']
        , dest: build
    },

    browsersync: {
        files: [build+'/**', '!'+build+'/**.map'] // Exclude map files
        , notify: false // In-line notifications (the blocks of text saying whether you are connected to the BrowserSync server or not)
        , open: true // Set to false if you don't like the browser window opening automatically
        , port: 3000 // Port number for the live version of the site; default: 3000
        , proxy: 'http://localhost/Netdust/CMS_03-2015_admin-dev/build/' // Using a proxy instead of the built-in server as we have server-side rendering to do via WordPress
        , watchOptions: {
            debounceDelay: 2000 // Delay for events called in succession for the same file/event
        }
    },

    livereload: {
        port: 35729
    },

    utils: {
        clean: [build+'**/.DS_Store'] // A glob matching junk files to clean out of `build`
        , wipe: [dist] // Clean this out before creating a new distribution copy
        , dist: {
            src: [build+'**/*', '!'+build+'**/*.min.css']
            , dest: dist
        }
    },

    watch: { // What to watch before triggering each specified task
        src: {
              styles:       src+'www/admin/css/**/*.css'
            , scripts:      [src+'www/admin/js/**/*.js']
            , images:       src+'**/*(*.png|*.jpg|*.jpeg|*.gif)'
            , theme:        [src+'**/*(*.html|*.tpl|*.php)']
            , livereload:   [build+'**/*']
        }
        , watcher: 'browsersync' // Who watches the watcher? Easily switch between BrowserSync ('browsersync') and Livereload ('livereload')
    }
};
