
// language
// @todo: make sure translation of page ( type attachement ) is not mandatory
// @todo: make sure meta translation is not necessary, default to main language when not set

//general
/* @todo: remove filters from data models, they are buggy */
/* @todo: delete users */
/* @todo: after create is success, redirect to list */
/* @todo: multi edit */
/* @todo: quick create button ?? */
/* @todo: add model-view binding so a rendered view is updated when model changes ( see publish state ) */
/* @todo: fix bug when creating page and selecting parent, parent is not set and app get unstable */
/* @todo: taxonomy */

//files
/* @todo: better file (upload) integration */
/* @todo: asset management */
/* @todo: ability to replace an image in a collection */
/* @todo: ability to add other media in collections */
/* @todo: make sure attachments with parent=0 and status draft are not messing up database */
/* @todo: BUG previewing uploaded files doesnt work */


//plugins
/* @todo: finish plugin settings */

require.config({

    'baseUrl'                        : base+'/../src',

    paths: {

        'app'                       : 'www/admin/js/app',
        'vendor'                    : '_system/bower',


        'ntdst'                     : "www/admin/js/app/ntdst",

        'jquery'                    : "_system/bower/jquery/dist/jquery",
        'jquery-ui'                 : "_system/bower/jquery-ui/jquery-ui",
        'jquery-ui/sortable'        : "_system/bower/jquery-ui/ui/sortable",
        'backbone'                  : "_system/bower/backbone/backbone",
        'backbone.subroute'         : '_system/bower/backbone.subroute/backbone.subroute',
        'backbone-forms'            : '_system/bower/backbone-forms/distribution.amd/backbone-forms',
        'backbone-relational'       : '_system/bower/backbone-relational/backbone-relational',
        'underscore'                : "_system/bower/underscore/underscore",
        'text'                      : '_system/bower/requirejs-text/text',
        'modernizr'                 : '_system/bower/modernizr/modernizr',
        'foundation'                : '_system/bower/foundation/js/foundation',
        'dropzone'                  : '_system/bower/dropzone/dist/dropzone-amd-module',
        'epiceditor'                : '_system/bower/epiceditor/epiceditor/js/epiceditor',

        'sortablelist'              : '_system/bower/nestedSortable/jquery.ui.nestedSortable',
        'listeditor'                : '_system/bower/backbone-forms/distribution.amd/editors/list',
        'spinner'                   : '_system/bower/spin.js/spin'
    },

    shim: {

        'underscore': {
            exports: '_'
        },
        'backbone-relational': {
            deps: ['backbone']
        },
        'backbone-forms': {
            deps: ['backbone']
        },
        'backbone.subroute': {
            deps: ['backbone']
        },
        'app/core/helper/editors': {
            deps: ['backbone']
        },

        'backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        'foundation': {
            deps: ['jquery','modernizr']
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

require( ['jquery', 'ntdst', 'app/api'],

    function ($, ntdst, api)
    {
        _.extend(ntdst.api, api);
        $(function() {
            ntdst.configure()
             .start();
        });
    });

