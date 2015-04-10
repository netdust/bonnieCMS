
define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        FormView            = require('app/core/view/FormView'),
        MetaView            = require('app/views/MetaView'),

        form                = require('text!app/module/collections/view/tpl/metaForm.html');

    return MetaView.extend({

        Form : FormView.extend({
            template: _.template( form )
        }),

        initialize:function ()
        {

            var _meta = new this.Form( {model:this.model} );
            this.addView( { '.panel' : _meta  } );

            ntdst.api.createLanguage( _meta );


            MetaView.prototype.initialize.apply(this, arguments);
        }

    });

});