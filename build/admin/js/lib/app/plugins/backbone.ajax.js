(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery','underscore','backbone'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('jquery','underscore','backbone'));
    } else {
        factory($,_,window.Backbone);
    }
})(function($,_,Backbone) {
    $(document).ajaxError(function (e, xhr, options) {
        console.log( arguments );
        Backbone.trigger('notification', { message: xhr.responseJSON['message'], type: 'warning' });
    });
    $(document).ajaxSuccess(function (e, xhr, options) {
        //Backbone.trigger('notification', { message: xhr.responseJSON['message'], type: 'success' });
    });
});