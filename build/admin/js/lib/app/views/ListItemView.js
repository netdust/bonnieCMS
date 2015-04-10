define(function (require) {

    "use strict";

    var $                   = require('jquery'),
        BaseView            = require('app/core/view/BaseView');

    return BaseView.extend({

        initialize:function () {
            //this.listenTo(this.model, 'all', this.render);
        },

        events: {
            "mouseover .item": "mouseOver",
            "mouseout .item": "mouseOut",
            "click .item": "showItem"
        },

        mouseOut: function(e) {
            $(e.currentTarget).css('background-color', 'transparent');
        },

        mouseOver: function(e) {
            $(e.currentTarget).css({'background-color':"#e9e9e9"});
        },

        showItem: function(e) {

        }


    });

});