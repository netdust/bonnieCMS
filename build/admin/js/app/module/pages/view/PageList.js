define(function (require) {

    "use strict";

    var ntdst               = require('ntdst');

    var $                   = require('jquery'),

        Base                = require('app/views/mainview'),

        SortList            = require('nestedSortable'),
        PageItemView        = require('app/module/pages/view/PageListItem');

    return Base.extend({

        events : {
            'click .button':'createItem'
        },

        data : {title:'Pages', label:'Create new Item'},

        initialize: function() {
            this.listenTo(this.model, 'reset', this.listRender);
        },

        listRender: function ()
        {

            var self = this;
            this.renderList();


            var o = $('.list .sortable-list').nestedSortable({
                forcePlaceholderSize: true,
                handle: 'span',
                helper:	'clone',
                items: 'li',
                opacity: .6,
                placeholder: 'placeholder',
                revert: 250,
                tabSize: 25,
                maxLevels: 4,
                isTree: true,
                expandOnHover: 700,
                startCollapsed: false,
                relocate: function(){
                    setTimeout(function(){
                        self.model.updateSort(
                            o.nestedSortable('toHierarchy', {startDepthCount: 0})
                        )
                    }, 300);
                }
            });



            $(document).foundation();

            return this;
        },


        renderList: function()
        {
            $('.data').html( '<div class="list"><ol class="sortable-list"></ol></div>' );
            this.menu_render(
                this.model, {'el':'.data ol.sortable-list', 'parent': 0 }
            );
            return this;
        },

        menu_render: function( model, p ) {

            var container = document.createDocumentFragment();
            var parent = p != null ? p.parent : null;
            var self = this;

            var models = model.getPages('parent', parent );

            models.sort().each( function(m)
            {
                var itm = self.getListItem( m );
                container.appendChild(itm);

                if( model.hasPages(m.get('id')) ) {
                    self.menu_render(
                        model, {
                            'el':$(itm).append('<ol class="sortable-list"></ol>').find('ol.sortable-list'), 'parent': m.get('id')
                        }
                    );
                }
            });

            $(p.el).append( container );
        },

        getListItem: function( m ) {
            return new PageItemView( {model:m} ).render().el
        },

        createItem: function() {
            ntdst.api.navigate( 'page/create' );
        },

        updatePageSort: function( index, id ) {
            var m = this.model.get(id);
            m.set('sort', index );
            m.save({sort: index}, {patch: true});
        }
    });

});