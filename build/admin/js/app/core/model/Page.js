
define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var _               = require('underscore'),
        Backbone        = require('backbone'),

        Translation     = require('app/core/model/Translation'),
        Translations    = require('app/core/model/TranslationCollection'),
        PageExtend      = require('app/core/model/PageExtend'),
        PageExtends     = require('app/core/model/PageExtendCollection'),

        models          = ntdst.models;


    models.basePage = Backbone.RelationalModel.extend({

        urlRoot: ntdst.options.api + "page",

        subModelTypes: {
            page: 'page',
            collection: 'collection',
            attachment: 'attachment'
        },

        relations: [
            {
                type: Backbone.HasMany,
                key: 'page_translation',
                relatedModel: Translation,
                collectionType: Translations,
                reverseRelation: {
                    key: 'page_id'
                }
            },
            {
                type: Backbone.HasMany,
                key: 'page',
                relatedModel: 'basePage',
                collectionType: 'pagesCollection',
                reverseRelation: {
                    key: 'parent'
                }
            },
            {
                type: Backbone.HasMany,
                key: 'page_meta',
                relatedModel: PageExtend,
                collectionType: PageExtends,
                reverseRelation: {
                    key: 'page_id'
                }
            }
        ],

        initialize: function () {
            this.on("invalid", function(model, error) {
                console.log( error );
            });
        },

        validate: function (attrs) {
            var errs = {};

            attrs = this.attributes;

            var translations = attrs['page_translation'];
            translations.each( function(model){
                _.extend( errs, model.validate() );
            } );

            var metas = attrs['page_meta'];
            metas.each( function(model){
                _.extend( errs, model.validate() );
            } );

            if (_.isEmpty(attrs.label)) errs.label = 'Invalid label';
            if (_.isEmpty(attrs.template)) errs.template = 'Invalid template';

            if(!_.isEmpty(errs)) return errs;
            return null;
        },

        toString: function() {
            return parseInt(this.get('id'));
        },

        updatePage: function()
        {
            var options = {};
            if( this.isNew() ) {
                this.temp = this.get('page');
                options = {
                    success: function (model, response) {
                        console.log(self.temp);
                        console.log(model);
                    },
                    error: function (model, response) {
                        console.log("error");
                    }
                }
            }

            this.save(null, options );
        },

        createCopy: function(options) {

            var clone = this.clone();
            options = options || {};
            if( options.url === undefined ) {
                options.url = this.urlRoot + '/' + this.get('id')  + '/copy/';
            }

            return Backbone.Model.prototype.fetch.call( clone, options );
        },

        updateStatus: function( status )
        {
            if(_.isUndefined(status))
            {
                if( this.get('status') == 'publish' ) {
                    status = 'draft'
                }
                else
                if( this.get('status') == 'draft' ) {
                    status = 'publish'
                }
            }

            if(!_.isUndefined(status)) {
                this.set('status', status );
                this.save({status: status}, {patch: true});
            }
            return status;
        },

        fetchMeta: function(options) {
            options = options || {};
            options.url = this.urlRoot + '/' + this.get('id') + '/meta';
            this.get('page_meta').fetch(options);
        },

        fetchTranslation: function(options) {
            options = options || {};
            options.url = this.urlRoot + '/' + this.get('id') + '/translation';
            this.get('page_translation').fetch(options);
        },

        hasTranslation : function()
        {
            if( this.get('page_translation').length > 0 ) {
                var m = this.get('page_translation').getTranslation( 0 );
                if(  m.length ) {
                    return m.models[0].isNew(); // if translation is new, this is the default one and we should try to load from db
                }
            }
            return false;
        },

        getTranslation: function ( id ) {
            id = ( _.isUndefined(id) ? ntdst.api.modelFactory('i18n').getIndex() : id );

            if( this.get('page_translation').length > 0 ) {
                var m = this.get('page_translation').getTranslation( id );

                if(  m.length ) {
                    return m.models[0]; // this will work, we only have 1 translation / id
                }
                else {
                    return this.get('page_translation').addTranslation(id); // if it doesn't exist add empty object, so we can save it later on
                }
            }

            return null;
        },

        hasSub : function()
        {
            return this.get('page').length > 0;
        },

        getSub: function ( id ) {

            var m = this.get('page').getPage( {id:id} );
            if(  m ) {
                return m;
            }

        }
    });

    return models.basePage;

});

