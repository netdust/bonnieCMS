
define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),
        FormView            = require('app/core/view/FormView'),

        pageform            = require('text!app/module/pages/view/tpl/form.html');

    return FormView.extend({

        template : _.template(pageform),

        updateTranslation:function(model) {
            this.model = model;
            this.initialize();
            this.render();
        },

        afterRender: function()
        {
            var self = this;
            if(_.isUndefined(this.model)) return;
            this.form.on('description:blur', function(event, field)
            {
                self.model.set( 'slug', self.toSlug( field.getValue() ) );
            });
        },

        toSlug: function(st){
            st = st.toLowerCase();
            st = st.replace(/[\u00C0-\u00C5]/ig,'a');
            st = st.replace(/[\u00C8-\u00CB]/ig,'e');
            st = st.replace(/[\u00CC-\u00CF]/ig,'i');
            st = st.replace(/[\u00D2-\u00D6]/ig,'o');
            st = st.replace(/[\u00D9-\u00DC]/ig,'u');
            st = st.replace(/[\u00D1]/ig,'n');
            st = st.replace(/[^a-z ]+/gi,'');
            st = st.trim().replace(/ /g,'-');
            st = st.replace(/[\-]{2}/g,'');
            return (st.replace(/[^a-z\- ]*/gi,''));
        }


    });

});