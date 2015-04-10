(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['backbone'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('backbone'));
    } else {
        root.urlbuilder = factory(root.Backbone);
    }
}(this, function (Backbone) {

    var className = 'backbone-loading';
    var onLoading = function () {
        document.documentElement.classList.add(className);
    };
    var onComplete = function () {
        document.documentElement.classList.remove(className);
    };

    var pendingAjaxCounter = 0;
    var backboneSync = Backbone.sync;

    Backbone.sync = function (method, model, options) {
        model.once('request', function (model, xhr, options) {
            xhr.always(function () {
                pendingAjaxCounter -= 1;
                if (pendingAjaxCounter === 0) {
                    onComplete();
                }
            });
        });
        pendingAjaxCounter += 1;
        if (pendingAjaxCounter === 1) {
            onLoading();
        }
        return backboneSync.apply(this, arguments);
    };

    return Backbone;
}));