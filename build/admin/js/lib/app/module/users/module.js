define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var SubRoute        = require('backbone.subroute'),

        Model           = require("app/module/users/model"),
        Users           = require("app/module/users/view/UserList"),
        User            = require("app/module/users/view/User"),

        router =  SubRoute.extend({

            routes: {
                "" : "userlist",
                "create" : "createUser",
                ":id" : "user"
            },

            user: function ( id )
            {
                var _m = ntdst.api.modelFactory( 'users', Model.userCollection );
                var view  = ntdst.api.viewFactory( 'user'+id, User, {model:_m.get({'id':id})});
                ntdst.api.show( '#app', view );
            },

            userlist: function ()
            {
                var _model = ntdst.api.modelFactory( 'users', Model.userCollection );
                var view  = ntdst.api.viewFactory( 'userlist', Users, {model:_model} );
                ntdst.api.show( '#app', view );

                this.listenTo(view, 'create', function() {
                    this.stopListening( view );
                    ntdst.api.navigate( 'user/create', true );
                });

                this.listenTo(view, 'show', function() {
                    this.stopListening( view );
                    //ntdst.api.navigate( 'user/create', true );
                    console.log( 'ok' );
                });
            },

            createUser: function () {
                var view  = new User( { model:new Model.user() } );
                ntdst.api.show( '#app', view );
            }

        }),

        users = {

            init: function ( options )
            {
                this.router = new router(options['path']);
                this.data( options['data'] );
                return this;
            },

            data:function( bootstrap ) {
                if( bootstrap != undefined ) {
                    var users = ntdst.api.modelFactory( 'users', Model.userCollection, bootstrap );
                    ntdst.models['user'] =  users.where({email: ntdst.options.user})[0];
                    return users;
                }
            }

        };

    return users;
});