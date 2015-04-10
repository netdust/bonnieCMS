define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),
        _                   = require('underscore'),

        Base                = require('app/views/mainview'),
        frm                 = require('text!app/module/settings/view/tpl/plugin.html');

    return Base.extend({

        form     : Base.extend( { template:_.template(frm) } ),
        htmlform : null,

        events : {
            'click .button':'updateItem',
            'change input':'updateModel'
        },

        data : {title:'Plugins', label:'Update'},

        initialize:function ()
        {
            this.htmlform = new this.form( {model:this.model} );
            this.data.title = 'Plugin settings';
        },

        render: function()
        {
            if (this.subviews) {
                _(this.subviews).invoke("remove");
                this.subviews = {};
            }

            this.subviews = {
                '.data' : this.htmlform
            };

            return Base.prototype.render.apply(this, arguments);
        },

        afterRender:function() {
            this.updateDescrList();
            this.updateSettingsList();
        },

        updateDescrList: function()
        {
            this.container = document.createDocumentFragment();
            _.each( {installed:this.model.get('installed'), name:this.model.get('label'), description:this.model.get('description') },this._addItem, this);
            $('.descrsettings').empty().append( this.container );
        },

        updateSettingsList: function()
        {
            this.container = document.createDocumentFragment();
            _.each( this.model.attributes,this._addPluginSetting, this);
            $('.pluginssettings').empty().append( this.container );
        },

        _addPluginSetting : function(  value, key ) {
            if( $.inArray( key, ['namespace','path','installed', 'label', 'description'] ) == -1 ) {
                this._addItem( value, key );
            }
        },

        _addItem : function(  value, key ) {

            var div = document.createElement('div');
            div.className = 'row collapse';
            div.innerHTML = '<div class="small-4 columns"><span class="prefix">'+key+'</span></div><div class="small-8 columns field"><input id="'+key+'" name="name" type="text" placeholder="" value="'+value+'"/></div>';
            this.container.appendChild(
                div
            );

        },

        updateModel: function( e ) {
            var o = {};
            var val = $(e.currentTarget).val();
            var key = $(e.currentTarget).attr('id');
            o[key] = val;

            this.model.set( o );
        },

        updateItem: function() {
            if(this.model.get('name')!='') {
                this.model.save();
            }
        }

    });

});