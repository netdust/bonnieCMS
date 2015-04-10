
define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var Backbone        = require('backbone'),
        Translation     = require('app/core/model/Translation'),

        TranslationCollection =  Backbone.Collection.extend({

            model: Translation,

            getTranslation: function( value ) {
                return new TranslationCollection( this.filter(
                    function(m) {
                        return m.get('language_id') == value;
                    }
                ) );
            },

            addTranslation: function( id ) {
                var t =  new this.model( {language_id:id} );
                this.add( t );
                return t;
            }

        });

    return TranslationCollection;

});

