define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');
    
    var SubRoute        = require('backbone.subroute'),

        Help            = require("app/module/help/view/Help"),

        router =  SubRoute.extend({

            routes: {
                "" : "help"
            },

            help: function ( )
            {
                var view  = ntdst.api.viewFactory( 'help', Help);
                ntdst.api.show( '#app', view );
            }

        }),

        help = {

            init: function ( options )
            {
                this.router = new router(options['path']);

                return this;
            }

        };

    return help;
});