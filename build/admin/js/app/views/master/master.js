define(function (require) {

    "use strict";

    var _                   = require('underscore'),

        BaseView            = require('app/core/view/BaseView'),
        NavView             = require('app/views/master/nav'),
        tpl                 = require('app/templates');

    return BaseView.extend({

        template: tpl.master,

        initialize: function () {
            var navview  = new NavView( {model: ntdst.models.modules} );
            this.subviews = {
                '#sidebar'      : navview
            }
        }

    });

});