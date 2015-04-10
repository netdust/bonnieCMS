
define(function (require) {

    "use strict";

    var ntdst                   = require('ntdst');

    var _                       = require('underscore'),
        Backbone                = require('backbone'),

        PageExtendI18n          = require('app/core/model/PageExtendTranslation'),
        TranslationCollection   = require('app/core/model/TranslationCollection');



    var PageExtendI18ns         = TranslationCollection.extend({
            model:PageExtendI18n
        });


    return Backbone.RelationalModel.extend({

        /*
        urlRoot: function() {
            return ntdst.options.api+ntdst.api.state.module() + "/meta";
        },*/

        urlRoot: ntdst.options.api + "pagemeta",

        relations: [
            {
                type: Backbone.HasMany,
                key: 'page_meta_translation',
                relatedModel: PageExtendI18n,
                collectionType: PageExtendI18ns,
                reverseRelation: {
                    key: 'meta_id'
                }
            }
        ],

        defaults: {
            page_meta_translation : [{}],
            field:"Text",
            key:""
        },

        schema: {
            //extend
            id:"Number",
            page_id:"Number",
            field:"Text", // type of field e.g Text, Number, Date
            key:"Text"
        },

        validate:function( attrs ) {
            var errs = {};

            attrs = this.attributes;

            // only check default translation, others are not required
            if( !_.isUndefined( this.get('page_meta_translation') ) )
            {
                if (_.isEmpty(attrs.field)) errs.field = 'A field needs to be defined';
                if (_.isEmpty(attrs.key)) errs.key = 'A key needs to be defined';

                var tr = this.getTranslation( 1 );
                _.extend( errs, tr.validate() );
            }

            if(!_.isEmpty(errs)) return errs;
            return null;
        },

        getTranslation: function ( id ) {
            id = ( _.isUndefined(id) ? ntdst.api.modelFactory('i18n').getIndex() : id );

            if( !_.isUndefined( this.get('page_meta_translation') ) ) {
                var m = this.get('page_meta_translation').getTranslation( id );

                if(  m.length ) {
                    return m.models[0]; // this will work, we only have 1 translation / id
                }
                else {
                    return this.get('page_meta_translation').addTranslation(id);
                }
            }

            return null;
        },

        toString: function() {
            return this.get('key');
        }
    });

});

