
define(function (require) {

    "use strict";

    var ntdst           = require('ntdst');

    var _               = require('underscore'),
        Backbone        = require('backbone'),

        Page            = require('app/core/model/Page'),

        models          = ntdst.models;


    models.pagesCollection = Backbone.Collection.extend({

        model: models.basePage,

        url:function() {
            return ntdst.options.api + ntdst.api.state.module();
        },

        initialize: function () {
            this.sort_key = 'sort';
        },

        getPage: function( obj ) {
            var p = this.get( obj );
            if( !_.isUndefined(p) ) {
                return p;
            }
        },

        hasPages:function(prnt) {
            var models = this.getPages('parent',prnt);
            return models.length>0;
        },

        getPages: function( key, value ) {
            value = value==0?null:value;
            return new models.pagesCollection( this.filter(
                function(m) {
                    if( isNaN( m.get(key) ) && ( m.get(key)!=null && key=='parent' ) )
                    {
                        return m.get(key).get('id') == value;
                    }
                    return m.get(key) == value;
                }
            ) );
        },

        getOptionList: function() {
            this.indentlist = '<option value="0">--</option>';
            this._getList(
                { 'depth':-1, 'parent': 0 }
            );
            return this.indentlist;
        },

        updateSort: function( l, p ) {
            this._sortItems( l, p );
            Backbone.sync('create', this, {
                url : ntdst.options.api + 'list/sort',
                success: function() {}
            });
        },

        _sortItems:function( l, p ) {
            var self = this;
            var parent = !_.isUndefined(p) ? p : 0;

            _.each( l, function( item, index )
            {
                var _i = self.get( {id:item.id} );

                if( !_.isUndefined(_i) ){
                    _i.set('sort', index);
                    _i.set('parent', parent);
                    if( _.isArray(item.children) ) {
                        self._sortItems( item.children, item.id );
                    }
                }
            });
        },

        _getList: function( p ) {

            var parent = (p != null ? p.parent : 0);
            var depth = (p != null ? p.depth : 0);
            var self = this;

            if( parent != undefined ) {
                var models = this.getPages('parent', parent );
                if( models.length>0) {
                    var ind = ++depth>0 ? Array(depth + 1).join( '-' )+' ': '';
                    models.sort().each( function(m)
                    {
                        self.indentlist+= '<option value="'+m.get('id')+'">'+(ind+m.get('label'))+'</option>';
                        self._getList(
                            {
                                'depth':depth, 'parent': m.get('id')
                            }
                        );
                    });
                }
            }
        },

        comparator: function(a, b) {
            a = parseInt( a.get(this.sort_key) );
            b = parseInt( b.get(this.sort_key) );
            return a > b ?  1
                : a < b ? -1
                :          0;
        }
    });

    return models.pagesCollection;

});

