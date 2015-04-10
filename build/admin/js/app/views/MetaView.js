define(function (require) {

    "use strict";

    var $                   = require('jquery'),
        _                   = require('underscore'),
        BaseView            = require('app/core/view/BaseView'),

        tpl                 = require('text!app/views/tpl/includes/metaview.html');

    return BaseView.extend({

        events: {
            "click #nav-open-btn": "toggleMeta"
        },

        template: _.template(tpl),

        toggleMeta: function() {
            $('#main').toggleClass('closed');
        },

        initialize: function() {
            BaseView.prototype.initialize.apply(this, arguments);
        },

        afterRender: function() {
            //$('#main').addClass( 'animate' );
        },

        onClose: function() {
            $('#main').removeClass( 'closed' );
        }

    });

});