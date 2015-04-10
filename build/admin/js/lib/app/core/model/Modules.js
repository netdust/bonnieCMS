define(function (require) {

    "use strict";

    //dynamic modules, import for r.js
    var m_collections       = require('app/module/collections/module'),
        m_help              = require('app/module/help/module'),
        m_pages             = require('app/module/pages/module'),
        m_settings          = require('app/module/settings/module'),
        m_users             = require('app/module/users/module');


    var Backbone    = require('backbone'),

        Module = Backbone.Model.extend({

            urlRoot: "module",

            initialize: function ()
            {
                var name = this.get('name');
                var obj = this.attributes;
                require(["app/module/"+name.toLowerCase()+ "/module"],
                    function( module ) {
                        module.init( obj );
                    });
            }

        }),

        ModulesCollection = Backbone.Collection.extend({
            model: Module,
            url: "module",
            module:function(m){
                if( m!=undefined ) this.route = m;
                return this.route;
            }
        });


    return {
        module: Module,
        modulesCollection: ModulesCollection
    };

});
