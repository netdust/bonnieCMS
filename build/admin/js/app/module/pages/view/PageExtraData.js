
define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        PageExtends         = require('app/core/model/PageExtendCollection'),

        BaseView            = require('app/core/view/BaseView'),
        fieldItem           = require('app/module/pages/view/PageExtraDataItem');

    return BaseView.extend({

        temp_collection:null,
        templates:null,
        tpl:null,

        events: {
            'click .button.addmeta' : "addField"
        },

        initialize:function( )  {
            this.template = this._template( this.model );
            return BaseView.prototype.initialize.apply(this, arguments);
        },

        render: function() {
            BaseView.prototype.render.apply(this, arguments);
            this.updateMetaList();
            return this;
        },

        updateTranslation:function(model)
        {
            this.model = model;
            this.updateMetaList();
        },

        updateMetaList: function()
        {
            // clear the tabs, make sure zombie objects are  gone too ( models && views )
            $( '.tabs-content .container').empty();

            this.model.each(this._addItem, this);
            this.temp_collection.each(this._addItem, this);
        },

        _addItem : function(  item ) {
            var view = new fieldItem( { model:item.getTranslation(), settings:item } );
            var templateinfo;

            if( this.tpl != undefined &&  (templateinfo = this.tpl.getField(item.get('key'))) != undefined ) {
                this.$el.find('#panel_' + templateinfo['tab'] + ' .container').append(
                    view.render().el
                );
            }
            else {
                this.$el.find( '#panel_meta .container').append(
                    view.render().el
                );
            }
        },

        addField: function() {
            var item = this.model.addItem( );
            var view = new fieldItem( { model:item.getTranslation(), settings:item } );
            $('#panel_meta .container').append(
                view.render().el
            );
        },

        _template: function( model )
        {



            var template_name = model.page_id.get('template'),
                tabshtml = $('<div class="columns small-8"><dl class="tabs" data-tab><dd class="active"><a href="#panel_meta">meta</a></dd></dl><div class="tabs-content"><div class="content active" id="panel_meta"><div class="panel"><div class="container"></div><a class="button green addmeta">+ Add metadata</a></div></div></div></div>'),
                fields, tabs,
                self = this;

            this.temp_collection = new PageExtends(  );

            this.templates = ntdst.api.modelFactory( 'templates' );
            this.tpl = this.templates.where({name:template_name})[0];

            if( this.tpl != undefined ) {
                tabs = this.tpl.getTabs();

                _.each( tabs, function(item) {
                    tabshtml.find('dl.tabs').append('<dd><a href="#panel_'+item+'">'+item+'</a></dd>');
                    tabshtml.find('div.tabs-content').append('<div class="content" id="panel_'+item+'"><div class="panel"><div class="container"></div></div></div>');
                } );


                this.fields = this.tpl.getFields();
                _.each( this.fields, function(item) {
                    if( !self.model.hasItemWithKey( item['key'] )  ) {
                        self.temp_collection.addItem(
                            {field:item['type'], key:item['key'], page_meta_translation:[{ key:item['key'], language_id:1 }] }
                        );
                    }
                } );

            }

            return  _.template( $('<div />').append(tabshtml.eq(0).clone()).html() );
        }

    });

});