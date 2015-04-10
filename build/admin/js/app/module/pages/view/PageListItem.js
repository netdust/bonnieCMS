define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),
        BaseListView        = require('app/views/ListItemView'),

        StringHelper        = require('app/core/helper/string'),

        tpl                 = require('text!app/module/pages/view/tpl/pagelistitem.html'),

        template = _.template(tpl);

    return BaseListView.extend({

        tagName:'li',

        events: {
            "mouseover .list-content": "mouseOver",
            "mouseout .list-content": "mouseOut",
            "click .pageinfo": "showItem",

            'click .option.edit':'editPage',
            'click .option.copy':'copyPage',
            'click .option.remove':'removePage',
            'click .option.publish':'statusPage'
        },

        render: function ()
        {
            this.$el.html(template(  _.extend( this.model.toJSON(), StringHelper ) ));
            this.$el.attr( 'id', 'menuItem_' + this.model.get('id') );
            this.$el.addClass( this.model.get('label') );
            this.$el.addClass( 'list-item' );

            this._setStatusLabel();

            return this;
        },

        showItem: function(e) {
            e.stopPropagation();
            ntdst.api.navigate( 'page/'+ this.model.get('id') );
        },

        editPage: function(e) {
            e.stopPropagation();
            ntdst.api.navigate( 'page/'+ this.model.get('id') );
        },

        copyPage: function(e) {
            e.stopPropagation();
            this.model.createCopy( {
                success : function(model) {
                    ntdst.api.navigate( 'page/'+ model.get('id'));
                }
            });
        },

        removePage: function(e) {
            e.stopPropagation();
            this.model.destroy();
            this.remove();
        },

        statusPage: function(e) {
            e.stopPropagation();

            this._setStatusLabel(
                this.model.updateStatus()
            );
        },

        _setStatusLabel: function( status )
        {

            var status = status || this.model.get('status');
            if( status == 'publish' ) {
                this.$el.find('.publish').first().html('Unpublish');
            }
            else
            if( status == 'draft' ) {
                this.$el.find('.publish').first().html('Publish');
            }
            else {
                this.$el.find('.publish').first().html('( private page )');
            }
        }


    });

});