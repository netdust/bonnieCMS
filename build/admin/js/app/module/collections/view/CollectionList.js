define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        Base                = require('app/module/pages/view/PageList'),
        PageItemView        = require('app/module/collections/view/PageListItem');

    return Base.extend({

        data : {title:'Collections', label:'Create new Set'},

        getListItem: function( m ) {
            return new PageItemView( {model:m} ).render().el
        },

        createItem: function() {
            ntdst.api.navigate( 'collection/create' );
        }

    });

});