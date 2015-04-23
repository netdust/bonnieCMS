define(function (require) {

    "use strict";

    var BaseView            = require('app/core/view/BaseView'),
        navitem             = require('app/views/master/navitem'),


        tpl                 = require('app/templates');

    return BaseView.extend({

        template: tpl.navigation,

        render: function ()
        {
            BaseView.prototype.render.apply(this, arguments);

            this.container = document.createDocumentFragment();
            this.$el.find('nav').append('<ul></ul>');
            this.model.each(this.addItem, this);
            this.$el.find('ul').append( this.container );

            return this;
        },

        addItem : function( item ) {
            this.container.appendChild(
                new navitem( {model: item } ).render().el
            );
        }

    });

});