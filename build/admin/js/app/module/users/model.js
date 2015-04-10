define(function (require) {

    "use strict";

    var ntdst    = require('ntdst');

    var Backbone = require('backbone'),

        User = Backbone.Model.extend({

            urlRoot: ntdst.options.api + "user",

            schema: {
                username:   { type: 'Text', message: 'Invalid username', validators: ['required'] },
                password:   { type: 'Password', message: 'Invalid password', validators: ['required'] },
                realname:   'Text',
                bio:        'Text',
                email:      {type: 'Text', message: 'Invalid email', validators: ['required', 'email'] },
                status:     {type: 'Select', options: ['inactive', 'active']},
                role:       {type: 'Select', options: ['administrator', 'publisher', 'editor', 'user'] },
                api_key:    'Text'
            },

            validate: function (attrs) {
                var errs = {};
                if(!_.isEmpty(errs)) return errs;
            },

            initialize: function () {
                this.on("invalid", function(model, error) {
                    console.log( error );
                });
            },

            hasPermission: function(p) {

                return true;
                //var perm = this.get('permissions');
                //return perm[p];
            }

        }),

        UserCollection = Backbone.Collection.extend({

            model: User,
            url: ntdst.options.api + "list/user",

            initialize: function () {

            }
        });


    return {
        user: User,
        userCollection: UserCollection
    };

});
