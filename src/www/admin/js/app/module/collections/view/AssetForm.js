define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        FormView            = require('app/core/view/FormView'),

        tpl                 = require('text!app/module/collections/view/tpl/asset.html');

    return FormView.extend({

        events : {
            'click .button.update':'updateAsset'
        },

        template : _.template(tpl),

        updateAsset: function() {
            this.model.save();
        }


    });

});