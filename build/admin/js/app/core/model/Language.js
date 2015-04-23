
define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var Backbone        = require('backbone'),


        Language =  Backbone.Model.extend({

            urlRoot: "language",

            defaults: {
                language:"nl"
            },

            initialize: function () {
                var lg = ntdst.options['languages'];
                this.set( 'language', lg[0] );
            },


            getIndex: function( )
            {
                var lg = ntdst.options['languages'];
                return lg.indexOf( this.get('language') ) + 1;
            },

            schema: {
                language : {type: 'Select', options:function(callback, editor) {
                    callback( ntdst.options['languages'] );
                } }
            }

        });

    return Language;

});
