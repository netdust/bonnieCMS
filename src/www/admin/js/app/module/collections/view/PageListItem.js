define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),
        BaseListView        = require('app/module/pages/view/PageListItem');


    return BaseListView.extend({

        showItem: function(e) {
            e.stopPropagation();
            ntdst.api.navigate( 'collection/'+ this.model.get('id') );
        },

        editPage: function(e) {
            e.stopPropagation();
            ntdst.api.navigate( 'collection/'+ this.model.get('id') );
        }

    });

});