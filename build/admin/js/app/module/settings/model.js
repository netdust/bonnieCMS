define(function (require) {

    "use strict";


    var ntdst       = require('ntdst');

    var Backbone    = require('backbone'),

        Meta = Backbone.Model.extend({

            urlRoot: ntdst.options.api + "meta",

            schema: {
                date_format:                'Text',
                'i18n.default':             { type: 'Text', message: 'Invalid language', validators: ['required'] },
                'i18n.languages':           'Text',
                home_page:                  'Text',
                description:                'Text',
                sitename:                   { type: 'Text', message: 'Invalid sitename', validators: ['required'] },
                theme:                      { type: 'Text', message: 'Invalid theme', validators: ['required'] },
                update_version:             'Text',
                homepage:                   'Text'
            },

            validate: function (attrs) {
                var errs = {};
                if(!_.isEmpty(errs)) return errs;
            },

            initialize: function () {

                this.on("invalid", function(model, error) {
                    console.log( error );
                });
            }

        }),

        Template = Backbone.Model.extend({
            urlRoot: ntdst.options.api + "template",

            tabs:[],
            fields:[],

            defaults: {
                path:                'path',
                label:               'label is required',
                description:         'description is required'
            },

            initialize: function () {

            },

            getField: function( key ) {

                var field = undefined;
                _.each( this.fields, function(item) {
                    if( key == item['key'] ) {
                        field = item;
                    }
                });

                return field;
            },

            getFields: function(  ) {
                if( this.fields.length>0) return this.fields;

                this.fields = [];
                if( this.get('parent') !== '' ){
                    var parent = this.collection.where({name:this.get('parent')})[0];
                    this.fields = parent.getFields();
                }

                this.fields = this.fields.concat( this.get('meta') );

                return this.fields
            },

            getTabs: function() {
                if( this.tabs.length>0) return this.tabs;

                this.tabs = [];
                if( this.get('parent') !== '' ){
                    var parent = this.collection.where({name:this.get('parent')})[0];
                    this.tabs = parent.getTabs();
                }

                var self = this;
                var meta = this.get('meta');

                _.each(meta, function(metaitem) {
                    var tab = metaitem['tab'];
                    if( !_.isUndefined(tab) && tab !== ''){
                        if( !_.contains(self.tabs, tab) ){
                            self.tabs.push( tab );
                        }
                    }
                });

                return this.tabs;
            }

        }),

        Templates = Backbone.Collection.extend({

            model: Template,
            url: ntdst.options.api + "template",

            initialize: function () {
                this.fetch();
            }

        }),


        Plugin = Backbone.Model.extend({
            urlRoot: ntdst.options.api + "plugin",

            defaults: {
                path:                'path',
                label:               'label is required',
                description:         'description is required'
            },

            initialize: function () {
                this.set( 'path', 'plugins/' + this.get('label') );
            }

        }),

        Plugins = Backbone.Collection.extend({
            model: Plugin,
            title: 'Plugins',
            url: ntdst.options.api + "list/plugin",

            initialize: function () {

            }
        });

    return {
        settingsCollection: Meta,
        templatesCollection: Templates,
        pluginsCollection: Plugins
    };

});
