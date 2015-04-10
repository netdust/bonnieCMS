
define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var Backbone        = require('backbone'),


        Language =  Backbone.Model.extend({

            urlRoot: "language",

            defaults: {
                language:"nl"
            },

            getIndex: function( )
            {
                var lg = ntdst.options['i18n.languages'].split(',');
                return lg.indexOf( this.get('language') ) + 1;
            },

            schema: {
                language : {type: 'Select', options:function(callback, editor) {
                    callback( ntdst.options['i18n.languages'].split(',') );
                } }
            }

        });

    return Language;

});
