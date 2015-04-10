define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),
        BaseListView        = require('app/views/ListItemView'),
        tpl                 = require('text!app/module/users/view/tpl/userlistitem.html'),

        template            = _.template(tpl);

    return BaseListView.extend({

        tagName:'li',

        events: {
            "mouseover .item": "mouseOver",
            "mouseout .item": "mouseOut",
            "click .item": "showItem"
        },

        render: function () {
            this.$el.html(template(this.model.toJSON()));
            return this;
        },

        showItem: function(e) {
            ntdst.api.navigate( 'user/'+$(e.currentTarget).data('id'), true );
        }


    });

});