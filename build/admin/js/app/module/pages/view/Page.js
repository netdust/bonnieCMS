define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        Base                = require('app/views/mainview'),

        MetaData            = require('app/module/pages/view/metaData'),
        PageData            = require('app/module/pages/view/pageData'),
        extraData           = require('app/module/pages/view/PageExtraData');


    return Base.extend({

        extend:undefined,
        translation:undefined,
        extend_view:undefined,
        translation_view:undefined,

        events : {
            'click .button.update':'updatePage',
            'click .option.status':'statusPage',
            'click .option.remove':'removePage'
        },

        initialize:function ( options )
        {
            this.metaform = options.metaform;

            this.listenTo( ntdst.api.modelFactory('i18n'), 'change:language', this.renderTranslations );
            this.listenTo( this.model, 'change:template', function() {
                this.extend_view.remove();
                this.extend_view = undefined;
                this.$('#contents').append('<div class="row extrarow"></div>'); // seriously bad coding here
                this.renderPageExtend();
            });
        },


        afterRender: function() {
            var self = this;
            $(document).foundation();
            setTimeout(function() {
                self._setStatusLabel()
            }, 300);
        },

        renderMeta: function()
        {
            this.extend_view = new extraData( { model:this.model.get('page_meta') } );
            this.translation_view = new PageData( { model:this.model.getTranslation() } );

            this.addView( {'.meta': new MetaData( { model:this.model } ) } );
            this.addView( {'.extrarow': this.extend_view } );
            this.addView( {'.maincontent': this.translation_view } );
        },

        renderTranslations: function() {
            this.renderTranslation();
            this.renderPageExtend();
        },

        renderTranslation: function()
        {

            this.translation = this.model.getTranslation();

            if( !_.isUndefined( this.translation  ) )
            {
                if(_.isUndefined(this.translation_view)) {

                    this.translation_view = new PageData( { model:this.translation } );
                    this.assign( {'.maincontent': this.translation_view } );
                }
                else {

                    this.translation_view.updateTranslation(
                        this.translation
                    )
                }
            }
        },

        renderPageExtend: function()
        {
            this.extend = this.model.get('page_meta');
            if( !_.isUndefined( this.extend ) )
            {
                if(_.isUndefined(this.extend_view))
                {
                    this.extend_view = new extraData( { model:this.extend } );
                    this.assign( {'.extrarow': this.extend_view } );
                }
                else {
                    this.extend_view.updateTranslation(
                        this.extend
                    )
                }
            }
        },

        render: function()
        {
            if (this.subviews) {
                _(this.subviews).invoke("remove");
                this.subviews = {};
            }

            this.renderMeta(); // add subviews
            Base.prototype.render.apply(this, arguments); // render page

            return this;
        },

        updatePage: function(e) {
            if(e.currentTarget == e.target ) {
                var errors = this.model.validate();
                if(_.isEmpty(errors))
                {
                    var self = this;
                    // merge temp models and then save; remove temp after save
                    if( self.extend_view.temp_collection != undefined ) {
                        self.model.get('page_meta').add(
                            self.extend_view.temp_collection.toJSON(),
                            {silent:true}
                        );
                    }

                    self.model.updatePage({
                        success: function (model, response) {
                            if( self.extend_view.temp_collection != null ) {
                                self.extend_view.temp_collection.reset();
                                self.extend_view.temp_collection = null;
                            }
                            self.renderTranslations();
                        }
                    });
                }
                else {
                    console.log( errors );
                    Backbone.trigger('notification', { message: 'Not all fields are filled in like they should, have a look', type: 'warning' });
                }
            }
        },

        removePage: function(e) {
            e.stopPropagation();
            this.model.destroy({success: function() {
                ntdst.api.navigate('page');
            }});
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
                this.$el.find('.pageoptions .option.status').first().html('Unpublish');
            }
            else
            if( status == 'draft' ) {
                this.$el.find('.pageoptions .option.status').first().html('Publish');
            }
            else {
                this.$el.find('.pageoptions .option.status').first().html('( private page )');
            }
        },

        remove:function() {
            if(!_.isUndefined(this.extend_view))
                this.extend_view.remove();
            if(!_.isUndefined(this.translation_view))
                this.translation_view.remove();

            var _lg = ntdst.api.modelFactory('i18n');
            this.stopListening( _lg, 'change:language' );

            return Base.prototype.remove.apply(this, arguments);
        }


    });

});