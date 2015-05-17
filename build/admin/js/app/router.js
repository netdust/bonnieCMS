define(function (require) {

    "use strict";

    var Backbone = require('backbone'),

        LoginView=require('app/core/view/LoginView');

    return Backbone.Router.extend({

        routes: {
            "":"dashboard",
            "login":"login",
            "forgot":"psw_forgot"
        },

        dashboard: function() {

        },

        login: function() {

            var view = ntdst.api.viewFactory( 'login', LoginView, {} );
            ntdst.api.show( '#app', view );
        }

    });

});