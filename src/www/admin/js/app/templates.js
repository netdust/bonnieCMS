define(function (require) {

    "use strict";

    var _  =  require('underscore');

    return {

        master : _.template( require('text!app/tpl/master.html')  ),

        navigation: _.template('<nav id="nav" role="navigation"></nav>'),
        navitem: _.template('<i data-path="<%= path %>" class="fa fa-<%= lowercase( icon ) %>" />'),

        login: _.template( require('text!app/tpl/login.html') )

    };

});