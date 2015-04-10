define(function (require) {

    "use strict";


    var ntdst       = require('ntdst');

    var Backbone    = require('backbone'),

        Config = Backbone.Model.extend({

            urlRoot: ntdst.options.api + "config",

            schema: {
                languages:                  'Text',
                home:                       'Text',
                description:                'Text',
                sitename:                   { type: 'Text', message: 'Invalid sitename', validators: ['required'] },
                theme:                      { type: 'Text', message: 'Invalid theme', validators: ['required'] }
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
            tabs:[],
            fields:[],

            defaults: {
                path:                'path',
                label:               'label is required',
                description:         'description is required'
            },

            initialize: function () {
                this.set( 'path', 'templates/' + this.get('label') );
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
                //if( this.fields.length>0) return this.fields;

                this.fields = [];
                if( this.get('parent') !== '' ){
                    var parent = this.collection.where({label:this.get('parent')})[0];
                    this.fields = parent.getFields();
                }

                this.fields = this.fields.concat( this.get('meta') );

                return this.fields
            },

            getTabs: function() {

                //if( this.tabs.length>0) return this.tabs;

                this.tabs = [];
                if( this.get('parent') !== '' ){
                    var parent = this.collection.where({label:this.get('parent')})[0];
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
                //this.fetch();
            },

            getNames:function() {
                var names = [];
                this.each(function(model){
                    names.push( model.get('label') );
                });

                return names;
            }

        }),


        Plugin = Backbone.Model.extend({
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
            url: ntdst.options.api + "plugin",

            initialize: function () {
            }
        }),

        ContentType = Backbone.Model.extend({
            defaults: {
                path:                'path',
                label:               'label is required',
                description:         'description is required'
            },

            schema: {
                description:                { type: 'Text', validators: ['required'] },
                label:                      { type: 'Text', message: 'Invalid label', validators: ['required'] }
            },

            initialize: function () {
                this.set( 'path', 'contenttypes/' + this.get('label') );
            }
        }),

        ContentTypes = Backbone.Collection.extend({
            model: ContentType,
            title: 'ContentTypes',
            url: ntdst.options.api + "contenttype",

            initialize: function () {

            },

            hasType: function( label ){
                return this.where({label:label.toLowerCase()}).length>0;
            },

            getType: function( label ){
                return this.where({label:label.toLowerCase()})[0];
            }
        });

    return {
        settingsCollection: Config,
        templatesCollection: Templates,
        pluginsCollection: Plugins,
        contenttypesCollection: ContentTypes
    };

});
