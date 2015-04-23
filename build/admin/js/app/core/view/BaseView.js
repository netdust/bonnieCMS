define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $           = require('jquery'),
        Backbone    = require('backbone');

    return Backbone.View.extend({

        ntdst: ntdst,
        subviews: {},

        template: null,

        templateData: function () {
            if (this.model) {
                return this.model.toJSON();
            }

            if (this.collection) {
                return this.collection.toJSON();
            }

            return {};
        },

        remove: function () {

            this.unbind();
            this.stopListening();

            if (this.subviews) {
                _(this.subviews).invoke("remove");
                this.subviews = {};
            }

            if (_.isFunction(this.onClose)){
                this.onClose();
            }

            this.trigger('remove');
            return Backbone.View.prototype.remove.apply(this, arguments);
        },

        render: function () {

            this.trigger('beforerender');
            if (_.isFunction(this.beforeRender)) {
                this.beforeRender();
            }

            this.$el.html(this.template(this.templateData()));
            this.assign( this.subviews );

            this.trigger('afterrender');
            if (_.isFunction(this.afterRender)) {
                this.afterRender();
            }

            return this;

        },

        assign : function (selector, view) {
            var selectors;
            if (_.isObject(selector)) {
                selectors = selector;
            }
            else {
                selectors = {};
                selectors[selector] = view;
            }
            if (!selectors) return;

            _.each(selectors, function (view, selector) {
                if (view) {
                    view.setElement(this.$(selector)).render();
                }
            }, this);
        },

        addView: function( obj ) {
            this.subviews = _.extend( obj, this.subviews );
        }

    });

});


