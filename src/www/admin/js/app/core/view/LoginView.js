define(function (require) {

    "use strict";

    var BaseView    = require('app/core/view/BaseView'),
        tpl         = require('app/templates');

    return BaseView.extend({

        template : tpl.login,

        events : {
            'click button' : 'submit'
        },

        submit : function(e){
            e.preventDefault();
            var email = $('#email').val();
            var password = $('#password').val();

/*
            Session.login({
                email : email,
                password : password
            });*/
        }
    });

});


