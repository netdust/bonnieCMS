
define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        StringHelper        = require('app/core/helper/string'),

        FormView            = require('app/core/view/FormView'),

        Models              = require('app/module/pages/model'),

        menuTpl             = require('text!app/module/pages/view/tpl/menuList.html');

    return FormView.extend({

        events: {
            'click .option' : "updateField",
            'load' : "onDomRefresh"
        },

        template : _.template(menuTpl),

        templateData: function () {
            return _.extend(this.model.toJSON(),{unique:StringHelper.random()});
        },

        initialize: function( options ) {
            this.settings = options.settings; // parent model

            this.listenTo(this.settings, 'change:field', this.render );
            return FormView.prototype.initialize.apply(this, arguments);
        },

        beforeRender :function()
        {

            var self = this;

            if( this.settings.get('field') == 'remove' ) {
                this.model.destroy();
                this.remove();
                return;
            }

            /* !!! schema is altered here, bad idea and could get buggy !!! */
            // change type of the field ( eg file, list, ... )
            var _o = this.model.schema.value;
            _o.type = this.settings.get('field').charAt(0).toUpperCase() + this.settings.get('field').slice(1);

            // check if it is a dynamic contenttype
            var _ct = ntdst.api.modelFactory('contenttypes');
            if( _ct.hasType( _o.type ) ){
                // we'll need to set the first item of the contenttypes as main view
                // _ct = _ct.getType( _o.type );
                // _o.type = _ct.get('meta')[0].type;
            }

            // help a little
            if( this.settings.get('field') == 'textarea' ) {
                _o.type = 'TextArea';
            }
            // make sure lists are arrays, not json
            if( this.settings.get('field') == 'list' ){
                var val = this.model.get('value');
                if( val == '' ) val=[];
                this.model.set('value', !_.isArray(val)?JSON.parse( val ):val );
            }

            // change schema placeHolder values
            this.model.schema.key.placeHolder = "Type label hier";
            this.model.schema.value.placeHolder = "Text";
            if( ntdst.api.modelFactory('i18n').getIndex()!=1 ) {
                var trans = this.settings.getTranslation(1);
                this.model.schema.key.placeHolder = trans.get('key');
                this.model.schema.value.placeHolder = trans.get('value');
            }

            // let go of prev form
            if(_.isObject(this.form) ) {
                this.form.off();
                this.form.unbind();
                this.form.remove();
            }

            this.form = new Backbone.Form( {
                schema:this.model.schema,
                model:this.model,
                template: this.template,
                templateData: this.templateData()
            } );

            // add key
            this.form.on('all', function(event, form, field) {
                if(event.indexOf("change") !== -1 && _.isObject(field) ) {
                    self.model.set( field.key, field.getValue() );

                    // set key on parend model. this we use in our templates
                    // no spaces, no special chars,
                    if( field.key == 'key' && ntdst.api.modelFactory('i18n').getIndex()==1 ) {
                        self.settings.set('key', self.toSlug( field.getValue() ) );
                    }
                }
            });

        },

        updateField: function( e ) {

            if( e.target.className.indexOf("contenttype") > -1 ) {

            }
            else {
                var classes = e.target.className.split(' ');
                var clss = classes[classes.length-1];

                this.settings.set('field', clss);
            }

        },

        toSlug: function(st){
            st = st.toLowerCase();
            st = st.replace(/[\u00C0-\u00C5]/ig,'a');
            st = st.replace(/[\u00C8-\u00CB]/ig,'e');
            st = st.replace(/[\u00CC-\u00CF]/ig,'i');
            st = st.replace(/[\u00D2-\u00D6]/ig,'o');
            st = st.replace(/[\u00D9-\u00DC]/ig,'u');
            st = st.replace(/[\u00D1]/ig,'n');
            st = st.replace(/[^a-z0-9 ]+/gi,'');
            st = st.trim().replace(/ /g,'-');
            st = st.replace(/[\-]{2}/g,'');
            return (st.replace(/[^a-z\- ]*/gi,''));
        }

    });

});