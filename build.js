({

    modules: [
        {
            name: 'admin'
            , exclude:['app-bootstrap']
        }
    ]

    , appDir: './src/www/admin/js'
    , baseUrl: "./"
    , dir: './dist/bonnie/admin/js'

    , fileExclusionRegExp: /^(r|build)\.js$/
    , optimizeCss: 'standard'

    , paths: {
        'ntdst'                     : "app/ntdst",

        'jquery'                    : "../../../_system/bower/jquery/dist/jquery",
        'jquery-ui'                 : "../../../_system/bower/jquery-ui/jquery-ui",
        'jquery-ui/sortable'        : "../../../_system/bower/jquery-ui/ui/sortable",

        'backbone'                  : "../../../_system/bower/backbone/backbone",
        'backbone.subroute'         : '../../../_system/bower/backbone.subroute/backbone.subroute',
        'backbone-forms'            : '../../../_system/bower/backbone-forms/distribution.amd/backbone-forms',
        'backbone-relational'       : '../../../_system/bower/backbone-relational/backbone-relational',
        'underscore'                : '../../../_system/bower/underscore/underscore',
        'text'                      : '../../../_system/bower/requirejs-text/text',
        'modernizr'                 : '../../../_system/bower/modernizr/modernizr',
        'foundation'                : '../../../_system/bower/foundation/js/foundation',
        'dropzone'                  : '../../../_system/bower/dropzone/dist/dropzone-amd-module',
        'epiceditor'                : '../../../_system/bower/epiceditor/epiceditor/js/epiceditor',
        'listeditor'                : '../../../_system/bower/backbone-forms/distribution.amd/editors/list',
        'sortablelist'              : '../../../_system/bower/nestedSortable/jquery.ui.nestedSortable',
        'spinner'                   : '../../../_system/bower/spin.js/spin'
    }

    , shim: {

        'underscore': {
            exports: '_'
        },
        'backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        'foundation': {
            deps: ['jquery','modernizr']
        },
        'epiceditor': {
            deps: ['jquery']
        },
        'dropzone': {
            deps: ['jquery']
        },
        'spinner': {
            deps: ['jquery']
        },
        'listeditor': {
            deps: ['backbone-forms']
        }

    }
});
