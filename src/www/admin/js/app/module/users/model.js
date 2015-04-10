define(function (require) {

    "use strict";

    var ntdst    = require('ntdst');

    var Backbone = require('backbone'),

        User = Backbone.Model.extend({

            urlRoot: ntdst.options.api + "user",

            schema: {
                username:   { type: 'Text', message: 'Invalid username', validators: ['required'] },
                password:   { type: 'Password', message: 'Invalid password', validators: ['required'] },
                first_name: 'Text',
                last_name:  'Text',
                phone:      'Text',
                email:      {type: 'Text', message: 'Invalid email', validators: ['required', 'email'] },
                active:     {type: 'Boolean'},
                role:       {type: 'Select', options: ['administrator', 'publisher', 'editor', 'user'] },
                api_key:    'Text'
            },
            default: {
                password:'***'
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
            url: ntdst.options.api + "user",

            initialize: function () {

            }
        });


    return {
        user: User,
        userCollection: UserCollection
    };

});
