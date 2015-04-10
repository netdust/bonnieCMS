
define(function (require) {

    "use strict";

    var ntdst      = require('ntdst');

    var Page       = require('app/core/model/Page'),
        Pages      = require('app/core/model/PageCollection'),

        models     = ntdst.models;


    models.collection = Page.extend({

        urlRoot: ntdst.options.api + "collection",

        defaults:  {
            user        : "admin",
            type        : 'collection',
            status      : 'draft',
            label       : "caption",
            template    : "default",
            parent      : "0",
            sort        : "0",
            page_translation : [{ language_id:1, slug:"new-collection", description:"", content:"" }]
        },

        schema:  {
            type        : {type: 'Select', options: ['page', 'collection', 'attachement'] },
            status      : {type: 'Select', options: function(callback, editor) {
                callback(  ntdst.api.hasPermission('publish_page') ? ['publish', 'private', 'draft'] : ['draft'] );
            } },
            parent      : {type: 'Select', options: function(callback, editor) {
                callback(  ntdst.api.modelFactory('collections').getOptionList() );
            } },
            date        : "DatePicker",
            user        : "Text",
            modified    : "Text",
            label       : {type:"Text", placeHolder:"Label", message: 'Invalid label', validators: ['required'] },
            template    : {type: 'Select', options: function(callback, editor) {
                callback(  ['default'] );
            }},
            sort        : "Number"
        },

        initialize: function () {
            //console.log( 'collection model created: ' , this );
            //console.log( this );
        }
    });

    models.attachment = Page.extend({

        urlRoot: ntdst.options.api + "attachment",

        defaults:  {
            user        : "admin",
            type        : 'attachment',
            status      : 'draft',
            label       : "caption",
            template    : "default",
            parent      : "0",
            sort        : "0",
            page_translation : [{ language_id:1, slug:"new-media", description:"", content:"" }]
        },

        schema:  {
            type        : {type: 'Select', options: ['page', 'collection', 'attachement'] },
            status      : {type: 'Select', options: function(callback, editor) {
                callback(  ntdst.api.hasPermission('publish_page') ? ['publish', 'private', 'draft'] : ['draft'] );
            } },
            parent      : 'PageCollection',
            date        : "DatePicker",
            user        : "Text",
            modified    : "Text",
            label       : {type:"Text", placeHolder:"Label", message: 'Invalid label', validators: ['required'] },
            template    : {type: 'Select', options: function(callback, editor) {
                callback(  ['default'] );
            }},
            sort        : "Number"
        },

        initialize: function () {
            /* make sure these settings are correct */
            this.set( 'status', 'inherit');
            this.set( 'type', 'attachment');
        },

        validate: function (attrs) {

            var errs = {};
            attrs = this.attributes;

            var metas = attrs['page_meta'];
            metas.each( function(model){
                _.extend( errs, model.validate() );
            } );

            if (_.isEmpty(attrs.label)) errs.label = 'Invalid label';
            if (_.isEmpty(attrs.template)) errs.template = 'Invalid template';

            if(!_.isEmpty(errs)) return errs;

            return null;
        }
    });


    return models;

});
