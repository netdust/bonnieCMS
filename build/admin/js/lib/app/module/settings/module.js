define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');
    
    var SubRoute        = require('backbone.subroute'),
        Model           = require("app/module/settings/model"),
        List            = require("app/module/settings/view/SettingsList"),
        PluginForm      = require("app/module/settings/view/PluginForm"),
        MetaForm        = require("app/module/settings/view/MetaForm"),
        FormView        = require('app/core/view/FormView'),

        router =  SubRoute.extend({
            routes: {
                "": "list",

                "general": "general",

                "plugins": "plugins",
                "plugins/:label": "plugin_settings",

                "templates": "templates",
                "templates/:label": "template_settings",

                "contenttypes": "contenttypes",
                "contenttypes/:label": "contenttype_settings"
            },

            list: function ( )
            {
                var _model = ntdst.api.modelFactory( 'settingslist', Backbone.Collection, [
                    { id:1, label:'General settings', path:'general', description:'Edit general settings of the website' },
                    { id:2, label:'ContentTypes', path:'contenttypes', description:'Manage contenttypes for use in templates' },
                    { id:3, label:'Templates', path:'templates', description:'Manage templates and template custom fields' },
                    { id:4, label:'Plugins', path:'plugins', description:'activate and manage plugins' }
                ]);
                var view  = ntdst.api.viewFactory( 'settingslist', List, {model:_model} );
                ntdst.api.show( '#app', view );
            },

            plugin_settings: function(label)
            {
                /*
                require([base+"/api/plug/"+label+'/test.js'],
                    function( module ) {
                        console.log('ok');
                    });
                    */

                var _model = ntdst.api.modelFactory( 'plugins', Model.pluginsCollection );
                _model = _model.select(function(m) {
                    return m.get("label") == label;
                });

                if( _model.length ) {
                    var view  = ntdst.api.viewFactory( 'plugin'+label, PluginForm, {model:_model[0]});
                    ntdst.api.show( '#app', view );
                }
                else ntdst.api.navigate('/setting/plugins');

            },

            contenttype_settings: function ( label )
            {
                var form = MetaForm.extend( {
                    data:{title:'Contenttype', label:'Update'},
                    meta:FormView.extend( { template:_.template('<div><fieldset><legend>Description</legend><div data-fields="label,description"></div></fieldset>') } )
                });

                var _model = ntdst.api.modelFactory( 'contenttypes', Model.contenttypesCollection );
                _model = _model.select(function(m) {
                    return m.get("label") == label;
                });

                var view  = ntdst.api.viewFactory( 'contenttype' + label, form, {model:_model[0]});
                ntdst.api.show( '#app', view );
            },

            contenttypes: function ( )
            {
                var _model = ntdst.api.modelFactory( 'contenttypes', Model.contenttypesCollection );
                var view  = ntdst.api.viewFactory( 'contenttypes', List, {model:_model});
                ntdst.api.show( '#app', view );
            },

            templates: function ( )
            {
                var _model = ntdst.api.modelFactory( 'templates', Model.templatesCollection );
                var view  = ntdst.api.viewFactory( 'templates', List, {model:_model});
                ntdst.api.show( '#app', view );
            },

            plugins: function ( )
            {
                var _model = ntdst.api.modelFactory( 'plugins', Model.pluginsCollection );
                var view  = ntdst.api.viewFactory( 'plugins', List, {model:_model});
                ntdst.api.show( '#app', view );
            },

            general: function ( )
            {
                var _model = ntdst.api.modelFactory( 'settings', Model.settingsCollection );
                var view  = ntdst.api.viewFactory( 'general', MetaForm, {model:_model});
                ntdst.api.show( '#app', view );
            }

        }),

        settings = {
            init: function ( options )
            {
                this.data( 'plugins', ntdst.options.plugins );
                this.data( 'templates', ntdst.options.templates );
                this.data( 'contenttypes', ntdst.options.contenttypes );

                this.data( options['name'], ntdst.options );
                this.router = new router( options['path'].toLowerCase());

                return this;
            },

            data:function( name, _data )
            {
                var n = name.toLowerCase();
                return ntdst.api.modelFactory( n, Model[n+'Collection'], _data );
            }
        };

    return settings;
});