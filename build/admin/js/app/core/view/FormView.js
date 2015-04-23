define(function (require) {

    "use strict";

    var $           = require('jquery'),
        Backbone    = require('backbone'),
        Backforms   = require('backbone-forms'),

        Editors     = require('app/core/helper/editors'),
        BaseView    = require('app/core/view/BaseView');


    return BaseView.extend({

        templateData: function () {
            return this.model.toJSON();
        },

        initialize:function ()
        {
            var self = this;

            if( !_.isUndefined(this.model) )
            {
                if(_.isObject(this.form) ) {
                    this.form.off();
                    this.form.unbind();
                    this.form.remove();
                }

                this.form =  new Backbone.Form( {
                    schema:this.model.schema,
                    model:this.model,
                    template: this.template,
                    templateData:this.templateData()
                } );

                this.listenTo(this.model, 'change', function( model ) {
                    _.each( model.changed, function( o, k ) {
                        if( !_.isUndefined( self.form ) && !_.isUndefined( self.form.fields[k] ) ) {
                            //self.form.setValue( k, o );
                        }
                    });
                });

                this.form.on('all', function(event, form, field)
                {
                    if((event.indexOf("change") !== -1 || event.indexOf("blur") !== -1 ) && _.isObject(field) )
                    {
                        self.model.set( field.key, field.getValue() );
                    }
                });
            }

        },

        validate: function() {
            return this.form.validate();
        },

        remove: function () {
            this.stopListening(this.model);
            if(_.isObject(this.form) ) {
                this.form.off();
                this.form.unbind();
                this.form.remove();
            }
            return BaseView.prototype.remove.apply(this, arguments);
        },

        render: function() {

            this.trigger('beforerender');
            if (_.isFunction(this.beforeRender)) {
                this.beforeRender();
            }

            if(_.isObject(this.form)) {
                this.$el.html( this.form.render().el );
            }

            if(_.isObject( this.subviews ) ) {
                this.assign( this.subviews );
            }

            this.trigger('afterrender');
            if (_.isFunction(this.afterRender)) {
                this.afterRender();
            }

            $(document).foundation();

            return this;

        }

    });

});


