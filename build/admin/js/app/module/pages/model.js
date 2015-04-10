/** TODO:
 * remove dependecies on APP namespace
 * models should not initialize other models either
 */

define(function (require) {

    "use strict";

    var ntdst             = require('ntdst');

    var Page              = require('app/core/model/Page'),
        Pages             = require('app/core/model/PageCollection'),
        models            = ntdst.models;


    models.page = Page.extend({

        defaults:  {
            user        : "admin",
            type        : 'page',
            status      : 'draft',
            label       : "caption",
            template    : "base",
            parent      : "0",
            sort        : "0",
            page_translation : [{ language_id:1, slug:"new-page", description:"", content:"" }]
        },

        schema:  {
            type        : {type: 'Select', options: ['page', 'collection', 'attachement'] },
            status      : {type: 'Select', options: function(callback, editor) {
                callback(  ntdst.api.hasPermission('publish_page') ? ['publish', 'private', 'draft'] : ['draft'] );
            } },
            parent      : "PageCollection",
            date        : "DatePicker",
            user        : "Text",
            modified    : "Text",
            label       : {type:"Text", placeHolder:"Label", message: 'Invalid label', validators: ['required'] },
            template    : {type: 'Select', options: function(callback, editor) {
                callback( ntdst.api.findTemplates( editor.model ) );
            }},
            sort        : "Number"
        },

        initialize: function () {

        }

    });

    return models;

});
