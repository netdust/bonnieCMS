define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');
    
    var SubRoute        = require('backbone.subroute'),

        Model           = require("app/module/pages/model"),
        Page            = require("app/module/pages/view/Page"),
        Pages           = require("app/module/pages/view/PageList"),


        router = SubRoute.extend({
            routes: {
                "" : "list",
                "create" : "create",
                ":id" : "page"
            },

            list: function ( )
            {
                var _models = ntdst.api.modelFactory( 'pages' );
                var view  = ntdst.api.viewFactory( 'pagelist', Pages, {model: _models} );
                ntdst.api.show( '#app', view );

                _models.fetch( {reset: true} );
            },

            page: function ( id )
            {
                var _m = ntdst.api.modelFactory( 'pages' ).getPage( id );

                if( _m == null ) {
                    ntdst.api.navigate('/page');
                    return;
                }

                // always load page, no cache
                _m.fetch( {reset:true} );

                this.listenTo(_m, 'sync', function()
                {
                    this.stopListening(_m, 'sync');

                    var view  = ntdst.api.viewFactory( 'page'+id, Page, {model:_m});
                    ntdst.api.show( '#app', view );

                });
            },

            create: function ( )
            {
                var _m = new Model.page( {date: new Date().getTime(), user:ntdst.options.user, type:"page"} );
                var view = new Page({model:_m});


                this.listenTo(view, 'afterrender', function() {
                    this.stopListening(view, 'afterrender');
                    $('#main').removeClass('closed');
                });

                this.listenTo(_m, 'sync', function() {
                    this.stopListening(_m, 'sync');
                    ntdst.api.navigate('/page');
                });

                ntdst.api.show( '#app', view );

            }

        }),

        pages = {
            init: function ( options )
            {
                this.data( options['name'], options['data'] );
                new router( options['path'].toLowerCase());
                return this;
            },

            data:function( name, _data ) {
                var n = name.toLowerCase();
                return ntdst.api.modelFactory( n, Model[n+'Collection'], _data );
            }
        };

    return pages;
});