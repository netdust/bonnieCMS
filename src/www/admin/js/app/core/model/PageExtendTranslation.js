
define(function (require) {

    "use strict";

    var ntdst       = require('ntdst');
    var Backbone    = require('backbone');


    return Backbone.RelationalModel.extend({

        urlRoot: ntdst.options.api + "pagemetatranslation",

        defaults: {
            key:"",
            value:""
        },

        schema: {
            language_id:"Number",
            key:{type:"Text", placeHolder:"Type label hier",message: 'Invalid key', validators: ['required']},
            value:{type:"Text", placeHolder:"Text"}
        },

        destroy:function() {
            var m = this.get('meta_id');
            if(m)m.destroy();
        },

        validate:function( attrs ) {
            var errs = {};

            if(_.isUndefined(attrs) ) attrs = this.attributes;
            if (_.isEmpty(attrs.key)) errs.key = 'A key needs to be defined';

            if(!_.isEmpty(errs)) return errs;
            return null;
        }
    });


});

