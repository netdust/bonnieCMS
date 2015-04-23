define(function (require) {

    "use strict";

    var _  =  require('underscore');

    return {

        master : _.template('<div class="loader"><i class="fa fa-refresh fa-spin" /></div><div class="wrapper"><section id="sidebar" ></section><section id="main" role="main"><div class="row"><form class="custom"><div class="columns small-12" id="app"></div> </form></div> <footer id="footer" role="contentinfo"></footer></section></div>'),

        navigation: _.template('<nav id="nav" role="navigation"></nav>'),
        navitem: _.template('<i data-path="<%= path %>" class="fa fa-<%= lowercase( icon ) %>" />')

    };

});