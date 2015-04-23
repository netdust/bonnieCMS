
(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery','underscore','backbone'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('jquery','underscore','backbone'));
    } else {
        factory($,_,window.Backbone);
    }
})(function($,_,Backbone) {

    Backbone.Notifications = {};

    _.extend(Backbone.Notifications, {
        initialize : function(config) {
            this.config = _.extend({
                el : 'body',
                //template: _.template('<div class="alert alert-<%= type %> alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><%= message %></div>'),
                template: _.template('<div data-alert class="alert-box <%= type %>" tabindex="0" aria-live="assertive" role="dialogalert"><%= message %><button href="#" tabindex="0" class="close" aria-label="Close Alert">&times;</button></div>'),
                stayDuration: 15000
            }, config);
            Backbone.on('notification', this.handleNotification, this);
        },
        handleNotification : function(p, opts) {
            var tempConfig = _.extend({}, this.config, opts),
                model = new this.flashModel(p, { config: tempConfig });
            view = new this.flashView({ model: model, config: tempConfig });
            $(tempConfig.el).prepend(view.render().el);
        },
        flashModel : Backbone.Model.extend({
            initialize: function(attr, options) {
                this.config = options.config;

                if(!this.get('persist'))
                    this.setTimer();
            },
            defaults: {
                persist: false,
                type: 'success',
                message: ''
            },
            setTimer: function() {
                var me = this;
                this.set('timeoutHandle', window.setTimeout(function() { me.destroy() }, this.config.stayDuration));
            },
            clearTimeout: function() {
                if(this.get('timeoutHandle'))
                    window.clearTimeout(this.get('timeoutHandle'));
            }
        }),
        flashView : Backbone.View.extend({
            tagName: 'div',
            className: 'flash',
            initialize: function(options) {
                this.config = options.config;
                this.listenTo(this.model, 'destroy', this.remove, this);
            },
            events: {
                'click button': 'close'
            },
            render: function() {
                this.$el.append(this.config.template(this.model.attributes));
                return this;
            },
            close: function() {
                this.model.clearTimeout();
                this.model.destroy();
            },
            remove: function() {
                var me = this;
                this.stopListening();
                this.$el.hide("slow", function() { me.$el.remove() });
            }
        })
    })


});