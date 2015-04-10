define(function (require) {

    "use strict";

    var BaseView            = require('app/core/view/BaseView'),
        navitem             = require('app/views/master/navitem'),

        container           = document.createDocumentFragment();

    return BaseView.extend({

        initialize: function () {

        },

        render: function ()
        {
            this.$el.append('<ul></ul>');
            this.model.each(this.addItem, this);
            this.$el.find('ul').append( container );
            return this;
        },

        addItem : function( item ) {
            container.appendChild(
                new navitem( {model: item } ).render().el
            );
        }

    });

});