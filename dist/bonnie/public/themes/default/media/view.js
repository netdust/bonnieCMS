define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        Base                = require('app/views/mainview'),
        FormView            = require('app/core/view/FormView'),
        BaseListView        = require('app/views/ListItemView');



    var ListItem = BaseListView.extend({

        tpl:'<div class="row collapse item" data-id="<%= id %>"><div class="columns small-8 pageinfo "><h2><%= firstname %> <%= lastname %></h2><h4>email: <%= email %></h4></div><div class="columns small-2 role text-right"><h5 class="radius secondary label"><%= lg %></h5></div></div>',

        tagName:'li',

        events: {
            "mouseover .item": "mouseOver",
            "mouseout .item": "mouseOut",
            "click .item": "showItem"
        },

        render: function () {
            var template = _.template(this.tpl);
            this.$el.html(template(this.model.toJSON()));
            return this;
        },

        showItem: function(e) {
            ntdst.api.navigate( 'player/'+$(e.currentTarget).data('id'), true );
        }


    });

    var List = Base.extend({

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
            this.updateList();

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
            this.trigger( 'create', 'user' );
        }
    });

    var Form =  Base.extend({

        tpl: '<div id="userForm"><div id="mainitems"><fieldset><legend>Player</legend><div data-fields="firstname,lastname,email,winner"></div></fieldset></div><div id="metaitems"><fieldset><legend>Address</legend><div data-fields="street,nr,city,code,country"></div></fieldset></div></div>',

        formview : null,

        events : {
            "change input" :"changed",
            "change select" :"changed",
            'click .button':'updateUser',
            "change .error input" :"remove_error"
        },

        data : {title:'Player info', label:'Update Player'},

        initialize:function ()
        {
            var playerview = FormView.extend( { template:_.template(this.tpl) } );

            this.formview = new playerview( {model:this.model} );
        },

        render:function() {

            if (this.subviews) {
                _(this.subviews).invoke("remove");
                this.subviews = {};
            }

            this.subviews = {
                '.data' : this.formview
            };

            return Base.prototype.render.apply(this, arguments);
        },

        updateUser: function() {
            var errors = this.formview.validate();
            if(_.isEmpty(errors)) {
                this.model.save();
            }
        },

        remove_error: function(e) {
            $(e.currentTarget).closest('div.error').removeClass('error');
        }


    });

    return {
        list:List,
        item:ListItem,
        form:Form
    }

});