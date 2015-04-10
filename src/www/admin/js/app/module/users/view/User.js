define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        FormView            = require('app/core/view/FormView'),
        Base                = require('app/views/mainview'),

        frm                 = require('text!app/module/users/view/tpl/user.html');

    return Base.extend({

        user : FormView.extend( { template:_.template(frm) } ),
        userform : null,

        events : {
            "change input" :"changed",
            "change select" :"changed",
            'click .button':'updateUser',
            "change .error input" :"remove_error"
        },

        data : {title:'User info', label:'Update User'},

        initialize:function ()
        {
            this.userform = new this.user( {model:this.model} );
        },

        render:function() {

            if (this.subviews) {
                _(this.subviews).invoke("remove");
                this.subviews = {};
            }

            this.subviews = {
                '.data' : this.userform
            };

            return Base.prototype.render.apply(this, arguments);
        },

        updateUser: function() {
            var errors = this.userform.validate();
            if(_.isEmpty(errors)) {
                this.model.save();
            }
        },

        remove_error: function(e) {
            $(e.currentTarget).closest('div.error').removeClass('error');
        }


    });

});