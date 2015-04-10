(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery','underscore','backbone'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('jquery','underscore','backbone'));
    } else {
        factory($,_,window.Backbone);
    }
})(function($,_,Backbone) {
    var defaultSync = Backbone.sync;
    Backbone.sync = function(method, model, options) {
        options = _.extend({
            beforeSend: function(xhr) {
                var token = $('meta[name="csrf_token"]').attr('content');
                if (token) xhr.setRequestHeader('X-CSRFToken', token);
            }
        }, options);
        defaultSync(method, model, options);
    };
});