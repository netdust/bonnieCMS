define(function (require) {

    "use strict";

    var $                   = require('jquery'),
        _                   = require('underscore'),
        BaseView            = require('app/core/view/BaseView'),

        StringHelper        = require('app/core/helper/string'),

        template            = _.template( '<i data-path="<%= path %>" class="fa fa-<%= lowercase( icon ) %>" />');

    return BaseView.extend({

        tagName: "li",

        events: {
            "click": "showSelect"
        },

        initialize:function () {
            this.listenTo(this.model, 'all', this.render);
        },

        render: function ()
        {
            this.$el.html( template( _.extend( this.model.toJSON(), StringHelper ) ) );
            return this;
        },

        showSelect: function( e ) {
            e.preventDefault();
            $(e.currentTarget).siblings().removeClass("is-active");
            $(e.currentTarget).addClass("is-active");

            ntdst.api.navigate( $(e.currentTarget).find('i').data('path') );
            return false;
        }


    });

});