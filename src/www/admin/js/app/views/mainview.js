define(function (require) {

    "use strict";

    var _                   = require('underscore'),
        BaseView            = require('app/core/view/BaseView'),

        tpl                 = require('text!app/views/tpl/Content.html');

    return BaseView.extend({

        data: {title:'', label:''},

        template : _.template(tpl),

        templateData: function () {
            return this.data
        }

    });

});