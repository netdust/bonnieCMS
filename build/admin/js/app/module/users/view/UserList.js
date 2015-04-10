define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        Base                = require('app/views/mainview'),
        ListItem            = require('app/module/users/view/UserListItem');

    return Base.extend({

        events : {
            'click .button':'addUser'
        },

        data : {title:'Users', label:'Create new User'},

        initialize:function ()
        {
            this.$ul = document.createElement('ul');
            this.listenTo(this.model, 'reset', this.updateList);
        },

        updateList: function()
        {
            this.container = document.createDocumentFragment();
            this.model.each(this.addItem, this);
            $('.data ul').empty().append( this.container );
        },

        render: function ()
        {
            Base.prototype.render.apply(this, arguments);

            $('.data').empty().append( this.$ul );
            $('.data ul').addClass('list');
            return  this;
        },

        remove: function () {
            $('.data ul').remove();
            this.$ul = document.createElement('ul');
            Base.prototype.remove.apply(this, arguments);
        },

        addItem : function( item ) {
            this.container.appendChild(
                new ListItem( {model:item} ).render().el
            );
        },

        addUser: function() {
            ntdst.api.navigate( 'user/create', true );
        }
    });

});