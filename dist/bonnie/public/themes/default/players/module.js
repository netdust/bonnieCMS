define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var SubRoute        = require('backbone.subroute'),

        View           = require('public/themes/default/players/view.js'),
        Model          = require('public/themes/default/players/model.js'),

        router =  SubRoute.extend({

            routes: {
                "" : "list",
                ":id" : "player"
            },

            list: function ()
            {


                var players = ntdst.api.modelFactory( 'players', Model.collection );
                players.fetch({'reset':true});
                this.listenTo(players, 'sync', function()
                {
                    this.stopListening(players, 'sync');
                    var v = ntdst.api.viewFactory( 'players', View.list, {model:players});
                    ntdst.api.show( '#app', v );
                });
            },

            player: function ( id )
            {
                var players = ntdst.api.modelFactory( 'players', Model.collection );
                var player = players.get({'id':id});

                var view  = ntdst.api.viewFactory( 'player'+id, View.form, {model:player});
                ntdst.api.show( '#app', view );
            }

        }),

        players = {

            init: function ( options )
            {
                console.log(options['path']);
                this.router = new router(options['path']);
                return this;
            }
        };

    return players;
});