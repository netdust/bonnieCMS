define(function (require) {

    "use strict";

    var _                   = require('underscore'),

        BaseView            = require('app/core/view/BaseView'),
        NavView             = require('app/views/master/nav');

    return BaseView.extend({

        template: _.template('<div class="loader"><i class="fa fa-refresh fa-spin" /></div><div class="wrapper"><section id="sidebar" ><nav id="nav" role="navigation"></nav></section><section id="main" role="main"><div class="row"><form class="custom"><div class="columns small-12" id="app"></div> </form></div> <footer id="footer" role="contentinfo"></footer></section></div>'),

        initialize: function () {

            var navview  = new NavView( {model: ntdst.models.modules} );

            this.subviews = {
                '#nav'      : navview
            }
        }

    });

});