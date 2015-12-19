define(function (require) {

    "use strict";

    var _               = require('underscore'),
        Backbone        = require('backbone');

    var PayerModel = Backbone.Model.extend({

        urlRoot: ntdst.options.api + "player",

        schema: {
            firstname: 'Text',
            lastname:  'Text',
            email:      {type: 'Text', message: 'Invalid email', validators: ['required', 'email'] },
            street:     'Text',
            nr:         'Text',
            city:       'Text',
            code:       'Number',
            country:    'Text',
            winner:     'Boolean'
        },

        initialize : function(){
        }

    });

    var PayersCollection = Backbone.Collection.extend({
        url: ntdst.options.api + "players",
        model: PayerModel
    });

    return {
        model: PayerModel,
        collection: PayersCollection
    };
});