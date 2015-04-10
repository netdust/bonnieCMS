define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),

        Base                = require('app/views/mainview'),
        ListItem            = require('app/module/settings/view/SettingsListItem');

    return Base.extend({

        data : {title:'Settings', label:''},

        initialize:function ()
        {
            this.$ul = document.createElement('ul');
            this.data.title = this.model.title || 'Settings';

            this.listenTo(this.model, 'reset', this.updateList);
        },

        render: function ()
        {
            Base.prototype.render.apply(this, arguments);
            $('.data').empty().append( this.$ul );
            $('.data ul').addClass('list');
            this.updateList();
            return  this;
        },

        updateList: function()
        {
            this.container = document.createDocumentFragment();
            this.model.each(this.addItem, this);
            $('.data ul').empty().append( this.container );
        },

        addItem : function( item ) {
            this.container.appendChild(
                new ListItem( {model:item} ).render().el
            );
        },

        remove: function () {
            $('.data ul').remove();
            this.$ul = document.createElement('ul');
            Base.prototype.remove.apply(this, arguments);
        }

    });

});