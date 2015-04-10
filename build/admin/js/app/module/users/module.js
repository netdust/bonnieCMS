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

                _model.fetch(
                    {reset:true, error: function() { console.log(arguments); } }
                );
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
                    return ntdst.api.modelFactory( 'user', Model.user, bootstrap );
                }
            }

        };

    return users;
});