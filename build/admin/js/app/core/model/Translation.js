
define(function (require) {

    "use strict";

    var ntdst            = require('ntdst');

    var Backbone        = require('backbone');


     return Backbone.RelationalModel.extend({

        urlRoot: ntdst.options.api + "translation",

        defaults: {
            slug: "page-slug",
            description: "",
            content: ""
        },

        schema: {
            id:'Number',
            page_id:'Number',
            language_id: "Number",
            slug: "Text",
            description: {type:"Text", placeHolder:"Title", message: 'Invalid title', validators: ['required']},
            content: {type:"Epic", placeHolder:"Pagina content, maak gebruik van markdown voor textformatting"}
        },

        toString: function() {
            return this.get('description');
        },

        validate: function (attrs) {
            var errs = {};

            if(_.isUndefined(attrs) ) attrs = this.attributes;

            if (_.isEmpty(attrs.description)) errs.description = 'Invalid title, (translation:'+this.get('language_id')+')';

            if(!_.isEmpty(errs)) return errs;
            return null;
        },

        initialize: function () {

        }

    });

});

