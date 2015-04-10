define(function (require) {

    "use strict";

    var $                   = require('jquery'),
        Backbone            = require('backbone'),


        AppBootstrap        = require('app-bootstrap'),

        // global libraries import
        Spinner             = require('spinner'),
        Foundation          = require('foundation'),

        // backbone plugins import
        ajax                = require('app/plugins/backbone.ajax'),
        csrf                = require('app/plugins/backbone.csrf'),
        flash               = require('app/plugins/backbone.flash'),
        click               = require('app/plugins/backbone.click'),
        loading             = require('app/plugins/backbone.loading'),


        //default config
        defaults = {
            pushState: true,
            lang:1
        };

    if( window.ntdst == undefined ) {

        var ntdst = window.ntdst = _.extend( {}, {
            api : {},
            user : {},
            views : {},
            models : {},
            events : {},
            options : {}
        });



        // event mixin
        Backbone.Notifications.initialize({el:'#main',stayDuration: 10000});
        _.extend(ntdst.events, Backbone.Events);


        // setting extra options
        _.extend(ntdst.options, defaults, AppBootstrap.settings);

    }
    else ntdst= window.ntdst;


    return ntdst;

});