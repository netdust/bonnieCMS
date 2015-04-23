
define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var Backbone            = require('backbone'),
        PageExtend          = require('app/core/model/PageExtend');


    var PageExtendCollection = Backbone.Collection.extend({
        model: PageExtend,

        hasItemWithKey: function( value ) {
            var c =  new PageExtendCollection( this.filter(
                function(m) {
                    return m.get('key') == value;
                }
            ) );
            return c.length > 0;
        },

        addItem: function( o ) {
            if( o==null ) o = {field: "text", key:"key", page_meta_translation:[{ language_id:1 }] };
            var e = new PageExtend( o );
            this.add( e );
            return e;
        }
    });

    return PageExtendCollection;

});

