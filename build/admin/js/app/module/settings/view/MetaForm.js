define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        FormView            = require('app/core/view/FormView'),
        Base                = require('app/views/mainview'),

        frm                 = require('text!app/module/settings/view/tpl/page.html');

    return Base.extend({

        meta     : FormView.extend( { template:_.template(frm) } ),
        metaform : null,

        events : {
            "change input" :"changed",
            "change select" :"changed",
            "change .error input" :"remove_error",
            'click .button':'updateMeta'
        },

        data : {title:'Settings', label:'Update'},

        initialize:function ()
        {
            this.metaform = new this.meta( {model:this.model} );
        },

        render: function()
        {

            if (this.subviews) {
                _(this.subviews).invoke("remove");
                this.subviews = {};
            }

            console.log( this.model );

            this.subviews = {
                '.data' : this.metaform
            };

            return Base.prototype.render.apply(this, arguments);
        },

        updateMeta: function() {
            var errors = this.metaform.validate();
            if(_.isEmpty(errors)) {
                this.model.save();
            }
        },

        remove_error: function(e) {
            $(e.currentTarget).closest('div.error').removeClass('error');
        }

    });

});