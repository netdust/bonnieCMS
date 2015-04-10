define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var SubRoute        = require('backbone.subroute'),

        router =  SubRoute.extend({

            routes: {
                "" : "assets"
            },

            assets: function ( )
            {

            }

        }),

        assets = {

            init: function ( options )
            {
                new router(options['path']);
                return this;
            }

        };

    return assets;
});