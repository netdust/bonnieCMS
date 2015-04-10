
define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        FormView            = require('app/core/view/FormView'),
        MetaView            = require('app/views/MetaView'),

        form                = require('text!app/module/pages/view/tpl/metaForm.html');

    return MetaView.extend({

        _meta: null,

        Form : FormView.extend({
            template: _.template( form )
        }),

        initialize:function ()
        {

            this._meta = new this.Form( {model:this.model} );
            this.addView( { '.panel' : this._meta  } );

            ntdst.api.createLanguage( this._meta );

            MetaView.prototype.initialize.apply(this, arguments);
        }

    });

});